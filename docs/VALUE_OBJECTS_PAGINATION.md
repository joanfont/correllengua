# Value Objects for Cursor Pagination

## Overview
This document describes the value objects implemented for cursor-based pagination, separating domain concerns from infrastructure implementation.

## Architecture

### Domain Layer (`src/Domain/Model/Common/`)
Contains pure domain value objects with business logic and validation.

### Application Layer (`src/Application/Query/Common/`)
Contains application-specific value objects that compose domain value objects for use in queries.

### Infrastructure Layer (`src/Infrastructure/Symfony/Http/ValueResolver/`)
Contains Symfony-specific value resolvers that automatically convert HTTP request parameters into value objects.

## Domain Value Objects

### `Cursor` (Domain Model)
**Location**: `src/Domain/Model/Common/Cursor.php`

A value object representing a pagination cursor with encoding/decoding capabilities.

#### Features:
- ✅ Immutable
- ✅ Self-validating
- ✅ Base64 encoding/decoding
- ✅ String representation

#### Usage:
```php
// From encoded string (from HTTP request)
$cursor = Cursor::fromEncoded('YTFiMmMzZDQ=');

// From raw value (internal use)
$cursor = Cursor::fromValue('a1b2c3d4-e5f6-7890');

// Get encoded value (for HTTP response)
$encoded = $cursor->encode(); // 'YTFiMmMzZDQ='

// Get raw value (for database queries)
$value = $cursor->value(); // 'a1b2c3d4-e5f6-7890'

// String conversion
$string = (string) $cursor; // 'a1b2c3d4-e5f6-7890'
```

#### Validation:
- ❌ Throws `InvalidArgumentException` if value is empty
- ❌ Throws `InvalidArgumentException` if base64 decoding fails

---

### `PageLimit` (Domain Model)
**Location**: `src/Domain/Model/Common/PageLimit.php`

A value object representing the maximum number of items per page.

#### Features:
- ✅ Immutable
- ✅ Self-validating (1-100 range)
- ✅ Default value (20)
- ✅ Helper method for query optimization

#### Constants:
- `MIN_LIMIT`: 1
- `MAX_LIMIT`: 100
- `DEFAULT_LIMIT`: 20

#### Usage:
```php
// From integer
$limit = PageLimit::fromInt(50);

// Default (20)
$limit = PageLimit::default();

// Get value
$value = $limit->value(); // 50

// For database queries (fetch one extra to detect next page)
$maxResults = $limit->plusOne(); // 51

// String conversion
$string = (string) $limit; // '50'
```

#### Validation:
- ❌ Throws `InvalidArgumentException` if < 1
- ❌ Throws `InvalidArgumentException` if > 100

---

## Application Value Objects

### `PaginationParameters`
**Location**: `src/Application/Query/Common/PaginationParameters.php`

Aggregates pagination-related domain value objects for use in queries.

#### Features:
- ✅ Immutable
- ✅ Composes domain value objects
- ✅ Factory methods for easy creation
- ✅ Handles cursor encoding/decoding

#### Properties:
- `PageLimit $limit` - The page size limit
- `?Cursor $cursor` - Optional pagination cursor

#### Usage:
```php
// Default pagination (20 items, no cursor)
$pagination = PaginationParameters::default();

// Custom pagination
$pagination = PaginationParameters::create(
    limit: 50,
    cursor: 'YTFiMmMzZDQ='
);

// In constructor (for queries)
public function __construct(
    public PaginationParameters $pagination,
) {}

// Access properties
$limit = $pagination->limit->value(); // 50
$cursorValue = $pagination->cursor?->value(); // 'a1b2c3d4-e5f6-7890'
```

---

### `EntityFilter`
**Location**: `src/Application/Query/Common/EntityFilter.php`

Represents filtering criteria for entities (Route, Itinerary, Segment) with priority handling.

#### Features:
- ✅ Immutable
- ✅ Type-safe (uses domain UUID value objects)
- ✅ Priority-based filtering (segment > itinerary > route)
- ✅ Factory methods for each filter type
- ✅ Query methods for introspection

#### Properties:
- `?RouteId $routeId` - Filter by route
- `?ItineraryId $itineraryId` - Filter by itinerary
- `?SegmentId $segmentId` - Filter by segment (highest priority)

#### Usage:
```php
// No filter
$filter = EntityFilter::none();

// Filter by route
$filter = EntityFilter::byRoute('550e8400-e29b-41d4-a716-446655440000');

// Filter by itinerary
$filter = EntityFilter::byItinerary('660e8400-e29b-41d4-a716-446655440000');

// Filter by segment
$filter = EntityFilter::bySegment('770e8400-e29b-41d4-a716-446655440000');

// Auto-prioritize (segment > itinerary > route)
$filter = EntityFilter::create(
    routeId: '550e8400-e29b-41d4-a716-446655440000',
    itineraryId: '660e8400-e29b-41d4-a716-446655440000',
    segmentId: '770e8400-e29b-41d4-a716-446655440000'
);
// Result: Only segmentId is set

// Check if filter is active
$hasFilter = $filter->hasFilter(); // true/false

// Get filter type
$type = $filter->getFilterType(); // 'segment' | 'itinerary' | 'route' | null

// Access filter values
if ($filter->segmentId) {
    $segmentId = (string) $filter->segmentId;
}
```

#### Filter Priority:
When multiple filters are provided, only the most specific is used:
1. **Segment** (highest priority) - Most specific
2. **Itinerary** - Medium specificity
3. **Route** (lowest priority) - Least specific

#### Validation:
- ❌ Throws `InvalidArgumentException` if UUID format is invalid (via domain value objects)

---

## Infrastructure Value Resolvers

### `PaginationParametersValueResolver`
**Location**: `src/Infrastructure/Symfony/Http/ValueResolver/PaginationParametersValueResolver.php`

Symfony value resolver that automatically converts HTTP query parameters into `PaginationParameters`.

#### Resolves:
- `limit` (query parameter) → `PageLimit` value object
- `cursor` (query parameter) → `Cursor` value object

#### Usage in Controllers:
```php
#[Route('/participants', methods: ['GET'])]
public function list(
    PaginationParameters $pagination, // Auto-resolved!
): JsonResponse {
    // $pagination->limit is already a PageLimit
    // $pagination->cursor is already a Cursor or null
}
```

#### HTTP Request:
```
GET /admin/participants?limit=50&cursor=YTFiMmMzZDQ=
```

#### Fallback:
If invalid values are provided, defaults to `PaginationParameters::default()`.

---

### `EntityFilterValueResolver`
**Location**: `src/Infrastructure/Symfony/Http/ValueResolver/EntityFilterValueResolver.php`

Symfony value resolver that automatically converts HTTP query parameters into `EntityFilter`.

#### Resolves:
- `routeId` (query parameter) → `RouteId` value object
- `itineraryId` (query parameter) → `ItineraryId` value object
- `segmentId` (query parameter) → `SegmentId` value object

#### Usage in Controllers:
```php
#[Route('/participants', methods: ['GET'])]
public function list(
    EntityFilter $filter, // Auto-resolved!
): JsonResponse {
    // $filter already contains validated UUID value objects
}
```

#### HTTP Request:
```
GET /admin/participants?segmentId=550e8400-e29b-41d4-a716-446655440000
```

#### Fallback:
If invalid UUIDs are provided, defaults to `EntityFilter::none()`.

---

## Complete Example

### Controller
```php
#[Route('/admin/participants', methods: ['GET'])]
public function listParticipants(
    QueryBus $queryBus,
    EntityFilter $filter,              // Auto-resolved from query params
    PaginationParameters $pagination,  // Auto-resolved from query params
): JsonResponse {
    $query = new ListParticipants(
        filter: $filter,
        pagination: $pagination,
    );

    $result = $queryBus->query($query);

    return $this->json($result);
}
```

### Query
```php
readonly class ListParticipants implements Query
{
    public function __construct(
        public EntityFilter $filter,
        public PaginationParameters $pagination,
    ) {}

    public static function create(
        ?string $routeId = null,
        ?string $itineraryId = null,
        ?string $segmentId = null,
        int $limit = 20,
        ?string $cursor = null,
    ): self {
        return new self(
            filter: EntityFilter::create($routeId, $itineraryId, $segmentId),
            pagination: PaginationParameters::create($limit, $cursor),
        );
    }
}
```

### Handler
```php
readonly class ListParticipantsHandler implements QueryHandler
{
    public function __invoke(ListParticipants $query): PaginatedResult
    {
        return $this->participantProvider->findAllPaginated(
            filter: $query->filter,
            pagination: $query->pagination,
        );
    }
}
```

### Provider
```php
public function findAllPaginated(
    EntityFilter $filter,
    PaginationParameters $pagination,
): PaginatedResult {
    $qb = $this->entityManager->createQueryBuilder()
        ->select('p')
        ->from(Participant::class, 'p')
        ->orderBy('p.id', 'ASC');

    // Apply filters
    if ($filter->segmentId) {
        $qb->andWhere('s.id = :segmentId')
            ->setParameter('segmentId', (string) $filter->segmentId);
    }

    // Apply cursor
    if ($pagination->cursor) {
        $qb->andWhere('p.id > :cursor')
            ->setParameter('cursor', $pagination->cursor->value());
    }

    // Apply limit
    $qb->setMaxResults($pagination->limit->plusOne());

    // ... rest of implementation
}
```

---

## Benefits

### 1. **Type Safety**
- All parameters are strongly typed
- PHPStan validates at max level
- No string/int confusion

### 2. **Validation**
- Validation happens at value object creation
- Invalid values are caught early
- Business rules enforced in domain layer

### 3. **Reusability**
- Value objects can be used across the application
- Consistent behavior everywhere
- Easy to test

### 4. **Separation of Concerns**
- Domain layer: business logic and validation
- Application layer: use case composition
- Infrastructure layer: HTTP/framework integration

### 5. **Maintainability**
- Single source of truth for validation rules
- Changes to limits/rules happen in one place
- Clear boundaries between layers

### 6. **Developer Experience**
- IDE autocompletion
- Clear method signatures
- Self-documenting code

---

## Testing

### Domain Value Objects
```php
public function testCreatesFromEncodedValue(): void
{
    $cursor = Cursor::fromEncoded(base64_encode('test'));
    self::assertSame('test', $cursor->value());
}

public function testThrowsExceptionForInvalidLimit(): void
{
    $this->expectException(InvalidArgumentException::class);
    PageLimit::fromInt(101);
}
```

### Application Value Objects
```php
public function testCreatesPaginationWithDefaults(): void
{
    $pagination = PaginationParameters::default();
    self::assertSame(20, $pagination->limit->value());
    self::assertNull($pagination->cursor);
}

public function testFilterPrioritizesSegment(): void
{
    $filter = EntityFilter::create(
        routeId: '...',
        itineraryId: '...',
        segmentId: '...'
    );
    self::assertNotNull($filter->segmentId);
    self::assertNull($filter->routeId);
    self::assertNull($filter->itineraryId);
}
```

---

## Migration Guide

### Before (Primitive Types)
```php
public function findAll(
    ?string $routeId,
    ?string $itineraryId,
    ?string $segmentId,
    int $limit,
    ?string $cursor,
): PaginatedResult {
    // Manual validation
    if ($limit < 1 || $limit > 100) {
        throw new \InvalidArgumentException('Invalid limit');
    }
    
    // Manual cursor decoding
    $lastId = $cursor ? base64_decode($cursor) : null;
    
    // ... implementation
}
```

### After (Value Objects)
```php
public function findAllPaginated(
    EntityFilter $filter,
    PaginationParameters $pagination,
): PaginatedResult {
    // Validation already done!
    // Type-safe access
    $lastId = $pagination->cursor?->value();
    $limit = $pagination->limit->value();
    
    // ... implementation
}
```

---

## Files Created

### Domain Layer (2)
1. `src/Domain/Model/Common/Cursor.php`
2. `src/Domain/Model/Common/PageLimit.php`

### Application Layer (2)
3. `src/Application/Query/Common/PaginationParameters.php`
4. `src/Application/Query/Common/EntityFilter.php`

### Infrastructure Layer (2)
5. `src/Infrastructure/Symfony/Http/ValueResolver/PaginationParametersValueResolver.php`
6. `src/Infrastructure/Symfony/Http/ValueResolver/EntityFilterValueResolver.php`

### Tests (4)
7. `tests/Unit/Domain/Model/Common/CursorTest.php`
8. `tests/Unit/Domain/Model/Common/PageLimitTest.php`
9. `tests/Unit/Application/Query/Common/PaginationParametersTest.php`
10. `tests/Unit/Application/Query/Common/EntityFilterTest.php`

### Modified Files (7)
1. `src/Domain/DTO/Common/Cursor.php` - Added factory method
2. `src/Application/Query/Participant/ListParticipants.php` - Uses value objects
3. `src/Application/Query/Participant/ListParticipantsHandler.php` - Updated signature
4. `src/Domain/Provider/Participant/ParticipantProvider.php` - Updated signature
5. `src/Infrastructure/Doctrine/Provider/Participant/DoctrineParticipantProvider.php` - Uses value objects
6. `src/Infrastructure/Symfony/Http/Controller/Admin/AdminController.php` - Uses value resolvers
7. `config/services.php` - Registered value resolvers
8. `tests/Unit/Application/Query/Participant/ListParticipantsTest.php` - Updated tests

**Total**: 10 new files, 8 modified files

