# Cursor-Based Pagination - Reusable Pattern

## Overview
This document describes the reusable cursor-based pagination mechanism implemented in the project.

## Core Components

### 1. Common DTOs
```php
// src/Domain/DTO/Common/Cursor.php
readonly class Cursor
{
    public function __construct(
        public ?string $next,
        public ?string $previous,
    ) {}
}

// src/Domain/DTO/Common/PaginatedResult.php
/** @template T */
readonly class PaginatedResult
{
    /** @param array<T> $items */
    public function __construct(
        public array $items,
        public Cursor $cursor,
        public int $total,
    ) {}
}
```

### 2. Provider Method Pattern
```php
interface YourProvider
{
    /**
     * @return PaginatedResult<YourDTO>
     */
    public function findAllPaginated(
        int $limit,
        ?string $cursor,
        // Add your specific filters here
    ): PaginatedResult;
}
```

### 3. Implementation Pattern
```php
public function findAllPaginated(int $limit, ?string $cursor): PaginatedResult
{
    $qb = $this->entityManager->createQueryBuilder()
        ->select('e')
        ->from(YourEntity::class, 'e')
        ->orderBy('e.id', 'ASC'); // Important: consistent ordering
    
    // Decode cursor
    $lastId = null;
    if (null !== $cursor) {
        $lastId = base64_decode($cursor, true);
        if (false !== $lastId) {
            $qb->andWhere('e.id > :cursor')
                ->setParameter('cursor', $lastId);
        }
    }
    
    // Get total count (before pagination)
    $countQb = clone $qb;
    $countQb->select('COUNT(DISTINCT e.id)');
    $total = (int) $countQb->getQuery()->getSingleScalarResult();
    
    // Fetch one extra to check for next page
    $qb->setMaxResults($limit + 1);
    $entities = $qb->getQuery()->getResult();
    
    // Check if there's a next page
    $hasNextPage = count($entities) > $limit;
    if ($hasNextPage) {
        array_pop($entities); // Remove extra item
    }
    
    // Map to DTOs
    $items = array_map(
        fn ($entity) => $this->factory->fromEntity($entity),
        $entities,
    );
    
    // Generate next cursor
    $nextCursor = null;
    if ($hasNextPage && count($entities) > 0) {
        $lastEntity = $entities[count($entities) - 1];
        $nextCursor = base64_encode((string) $lastEntity->id());
    }
    
    return new PaginatedResult(
        items: $items,
        cursor: new Cursor(next: $nextCursor, previous: null),
        total: $total,
    );
}
```

### 4. Query Pattern
```php
/**
 * @implements Query<PaginatedResult<YourDTO>>
 */
readonly class ListYourEntities implements Query
{
    public function __construct(
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public int $limit = 20,
        public ?string $cursor = null,
        // Add your filters here
    ) {}
}
```

### 5. Handler Pattern
```php
readonly class ListYourEntitiesHandler implements QueryHandler
{
    public function __construct(
        private YourProvider $provider,
    ) {}
    
    /**
     * @return PaginatedResult<YourDTO>
     */
    public function __invoke(ListYourEntities $query): PaginatedResult
    {
        return $this->provider->findAllPaginated(
            limit: $query->limit,
            cursor: $query->cursor,
        );
    }
}
```

### 6. Controller Pattern
```php
#[Route('/your-endpoint', name: 'list_your_entities', methods: ['GET'])]
public function list(
    QueryBus $queryBus,
    #[MapQueryParameter] int $limit = 20,
    #[MapQueryParameter] ?string $cursor = null,
): JsonResponse {
    $query = new ListYourEntities(
        limit: $limit,
        cursor: $cursor,
    );
    
    $result = $queryBus->query($query);
    
    return $this->json($result);
}
```

### 7. OpenAPI Response Pattern
```php
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: new Model(type: YourResponse::class)),
        ),
        new OA\Property(
            property: 'cursor',
            ref: new Model(type: CursorResponse::class),
        ),
        new OA\Property(property: 'total', type: 'integer'),
    ],
    type: 'object',
)]
final readonly class PaginatedYourResponse { /* ... */ }
```

## Usage Examples

### Client-Side Implementation

#### JavaScript/TypeScript
```typescript
interface PaginatedResponse<T> {
  items: T[];
  cursor: {
    next: string | null;
    previous: string | null;
  };
  total: number;
}

async function fetchAllPages<T>(
  baseUrl: string,
  limit: number = 20
): Promise<T[]> {
  const allItems: T[] = [];
  let cursor: string | null = null;
  
  do {
    const url = new URL(baseUrl);
    url.searchParams.set('limit', limit.toString());
    if (cursor) {
      url.searchParams.set('cursor', cursor);
    }
    
    const response = await fetch(url.toString(), {
      headers: {
        'Authorization': 'Basic ' + btoa('username:password')
      }
    });
    
    const data: PaginatedResponse<T> = await response.json();
    allItems.push(...data.items);
    cursor = data.cursor.next;
  } while (cursor !== null);
  
  return allItems;
}

// Usage
const participants = await fetchAllPages('/admin/participants', 50);
```

#### PHP/Guzzle
```php
function fetchAllPages(string $baseUrl, int $limit = 20): array
{
    $client = new \GuzzleHttp\Client([
        'auth' => ['username', 'password'],
    ]);
    
    $allItems = [];
    $cursor = null;
    
    do {
        $response = $client->get($baseUrl, [
            'query' => array_filter([
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
        ]);
        
        $data = json_decode($response->getBody(), true);
        $allItems = array_merge($allItems, $data['items']);
        $cursor = $data['cursor']['next'];
    } while ($cursor !== null);
    
    return $allItems;
}

// Usage
$participants = fetchAllPages('/admin/participants', 50);
```

## Best Practices

### 1. Cursor Encoding
- Always use base64 encoding for cursors
- Use the entity's primary key (ID) as the cursor value
- Validate decoded cursors before use

### 2. Query Optimization
- Always use consistent ordering (usually by ID)
- Clone query builder before counting to avoid side effects
- Use `DISTINCT` when joining related entities
- Fetch `limit + 1` items to check for next page existence

### 3. Validation
- Limit the maximum page size (e.g., 100)
- Set a reasonable default (e.g., 20)
- Validate cursor format and content

### 4. Error Handling
- Handle invalid cursor gracefully (treat as null)
- Return empty results rather than errors for invalid cursors
- Log suspicious cursor values for security monitoring

### 5. Performance
- Index the columns used in cursor conditions (usually ID)
- Consider caching total count for large datasets
- Use `->distinct()` to avoid duplicate results with joins

## Testing Pattern

```php
public function testReturnsPaginatedResults(): void
{
    // Arrange
    $items = [/* create test items */];
    $nextCursor = base64_encode('last-id');
    
    $paginatedResult = new PaginatedResult(
        items: $items,
        cursor: new Cursor(next: $nextCursor, previous: null),
        total: 100,
    );
    
    $this->provider
        ->expects($this->once())
        ->method('findAllPaginated')
        ->with(10, null)
        ->willReturn($paginatedResult);
    
    // Act
    $query = new ListYourEntities(limit: 10);
    $result = self::handleQuery($query);
    
    // Assert
    self::assertInstanceOf(PaginatedResult::class, $result);
    self::assertSame($nextCursor, $result->cursor->next);
    self::assertSame(100, $result->total);
}
```

## Migration Guide

### From Simple Array Returns

**Before:**
```php
interface OldProvider {
    /** @return array<Item> */
    public function findAll(): array;
}
```

**After:**
```php
interface NewProvider {
    /** @return PaginatedResult<Item> */
    public function findAllPaginated(int $limit, ?string $cursor): PaginatedResult;
}
```

### Backward Compatibility

Keep both methods during migration:
```php
interface Provider {
    /** @return array<Item> */
    public function findAll(): array;
    
    /** @return PaginatedResult<Item> */
    public function findAllPaginated(int $limit, ?string $cursor): PaginatedResult;
}

// In implementation
public function findAll(): array
{
    return $this->findAllPaginated(1000, null)->items;
}
```

## Common Pitfalls

### ❌ Wrong: Inconsistent Ordering
```php
// Don't use random ordering or non-deterministic fields
->orderBy('RAND()')  // Bad!
->orderBy('e.updatedAt')  // May have duplicates
```

### ✅ Right: Consistent Ordering
```php
// Always use ID or a unique, indexed field
->orderBy('e.id', 'ASC')
```

### ❌ Wrong: Not Handling Cursor Validation
```php
// Don't blindly trust cursor
$lastId = base64_decode($cursor);  // Could be false!
```

### ✅ Right: Validate Cursor
```php
$lastId = null;
if (null !== $cursor) {
    $lastId = base64_decode($cursor, true);
    if (false !== $lastId) {
        // Use cursor
    }
}
```

### ❌ Wrong: Calculating Total on Every Request
```php
// Don't count on every page request if dataset is huge
$total = $countQb->getQuery()->getSingleScalarResult();  // Expensive!
```

### ✅ Right: Cache Total for Large Datasets
```php
// Cache total count for large datasets
$total = $cache->get('total_count', function() use ($countQb) {
    return (int) $countQb->getQuery()->getSingleScalarResult();
});
```

## Reference Implementation

See `src/Infrastructure/Doctrine/Provider/Participant/DoctrineParticipantProvider.php` for a complete working example with:
- Cursor-based pagination
- Filtering
- Efficient querying
- Proper error handling

