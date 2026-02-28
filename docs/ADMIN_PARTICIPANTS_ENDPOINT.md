# Admin Participants Endpoint

## Overview
A secure admin endpoint that provides paginated listing of all participants with their registrations. This endpoint supports filtering by route, itinerary, or segment and implements cursor-based pagination for efficient data retrieval.

## Endpoint

**GET** `/admin/participants`

## Authentication
This endpoint requires basic HTTP authentication. All requests must include valid credentials.

## Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `routeId` | UUID | No | - | Filter participants by route ID |
| `itineraryId` | UUID | No | - | Filter participants by itinerary ID |
| `segmentId` | UUID | No | - | Filter participants by segment ID |
| `limit` | Integer | No | 20 | Number of items per page (1-100) |
| `cursor` | String | No | - | Cursor for pagination |

## Response Schema

```json
{
  "items": [
    {
      "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
      "name": "John",
      "surname": "Doe",
      "email": "john.doe@example.com",
      "registrations": [
        {
          "id": "r1a2b3c4-d5e6-7890-abcd-ef1234567890",
          "modality": "WALK",
          "participant": {
            "id": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
            "name": "John",
            "surname": "Doe",
            "email": "john.doe@example.com"
          },
          "segment": {
            "id": "s1a2b3c4-d5e6-7890-abcd-ef1234567890",
            "name": "Segment 1",
            "order": 1,
            "distance": 5.2,
            "modality": "WALK",
            "capacity": 50,
            "registrations": 25
          }
        }
      ]
    }
  ],
  "cursor": {
    "next": "bmV4dENvcnNvcg==",
    "previous": null
  },
  "total": 150
}
```

## Examples

### List all participants (paginated)
```bash
curl -X GET "http://localhost/admin/participants" \
  -u "username:password"
```

### List participants with custom page size
```bash
curl -X GET "http://localhost/admin/participants?limit=50" \
  -u "username:password"
```

### Filter by segment
```bash
curl -X GET "http://localhost/admin/participants?segmentId=550e8400-e29b-41d4-a716-446655440000" \
  -u "username:password"
```

### Filter by itinerary
```bash
curl -X GET "http://localhost/admin/participants?itineraryId=660e8400-e29b-41d4-a716-446655440000" \
  -u "username:password"
```

### Filter by route
```bash
curl -X GET "http://localhost/admin/participants?routeId=770e8400-e29b-41d4-a716-446655440000" \
  -u "username:password"
```

### Navigate with cursor
```bash
curl -X GET "http://localhost/admin/participants?cursor=bmV4dENvcnNvcg==" \
  -u "username:password"
```

## Response Codes

| Code | Description |
|------|-------------|
| 200 | Success - Returns paginated list of participants |
| 400 | Bad Request - Invalid parameters (e.g., invalid UUID format) |
| 401 | Unauthorized - Authentication required |
| 500 | Internal Server Error |

## Implementation Details

### Architecture
The endpoint follows the CQRS pattern with:
- **Query**: `App\Application\Query\Participant\ListParticipants`
- **Handler**: `App\Application\Query\Participant\ListParticipantsHandler`
- **Provider**: `App\Domain\Provider\Participant\ParticipantProvider`
- **Controller**: `App\Infrastructure\Symfony\Http\Controller\Admin\AdminController`

### Pagination
This endpoint implements cursor-based pagination:
- Cursors are base64-encoded participant IDs
- The `next` cursor in the response can be used to fetch the next page
- Results are ordered by participant ID (ASC)
- The `total` field provides the total count of participants matching the filters

### Filtering
Filters are mutually exclusive and applied in this priority:
1. `segmentId` - Most specific
2. `itineraryId` - Medium specificity
3. `routeId` - Least specific

If multiple filters are provided, only the most specific one is applied.

### Security
- All `/admin/*` routes require authentication
- Access is controlled via Symfony Security component
- Basic HTTP authentication is used

## Testing

Unit tests are located at:
- `tests/Unit/Application/Query/Participant/ListParticipantsTest.php`

Run tests with:
```bash
make test
```

Run type checks with:
```bash
make phpstan
```

