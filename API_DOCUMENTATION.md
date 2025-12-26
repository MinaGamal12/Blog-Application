# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

All protected endpoints require JWT authentication. Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## Endpoints

### Authentication

#### Register
- **POST** `/register`
- **Description**: Register a new user
- **Auth Required**: No
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "image": "file" // optional, multipart/form-data
  }
  ```
- **Response** (201):
  ```json
  {
    "message": "User successfully registered",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "image": "users/image.jpg"
    },
    "token": "jwt_token_here"
  }
  ```

#### Login
- **POST** `/login`
- **Description**: Login user
- **Auth Required**: No
- **Request Body**:
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response** (200):
  ```json
  {
    "message": "Login successful",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "jwt_token_here"
  }
  ```

#### Logout
- **POST** `/logout`
- **Description**: Logout user
- **Auth Required**: Yes
- **Response** (200):
  ```json
  {
    "message": "Successfully logged out"
  }
  ```

#### Get Current User
- **GET** `/user`
- **Description**: Get authenticated user details
- **Auth Required**: Yes
- **Response** (200):
  ```json
  {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "image": "users/image.jpg"
  }
  ```

#### Update User
- **PUT** `/user`
- **Description**: Update authenticated user
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "name": "John Updated",
    "email": "johnupdated@example.com",
    "password": "newpassword123",
    "image": "file" // optional, multipart/form-data
  }
  ```
- **Response** (200):
  ```json
  {
    "message": "User updated successfully",
    "user": {
      "id": 1,
      "name": "John Updated",
      "email": "johnupdated@example.com"
    }
  }
  ```

---

### Posts

#### Get All Posts
- **GET** `/posts`
- **Description**: Get all posts with author, tags, and comments
- **Auth Required**: Yes
- **Response** (200):
  ```json
  [
    {
      "id": 1,
      "title": "Post Title",
      "body": "Post body content",
      "author": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "image": "users/image.jpg"
      },
      "tags": [
        {"id": 1, "name": "laravel"},
        {"id": 2, "name": "php"}
      ],
      "comments": [...],
      "created_at": "2024-01-01T00:00:00.000000Z",
      "expires_at": "2024-01-02T00:00:00.000000Z",
      "is_expired": false
    }
  ]
  ```

#### Get Single Post
- **GET** `/posts/{id}`
- **Description**: Get a single post by ID
- **Auth Required**: Yes
- **Response** (200): Same structure as Get All Posts

#### Create Post
- **POST** `/posts`
- **Description**: Create a new post
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "title": "New Post",
    "body": "Post content here",
    "tags": ["laravel", "php", "react"]
  }
  ```
- **Response** (201):
  ```json
  {
    "message": "Post created successfully",
    "post": {...}
  }
  ```

#### Update Post
- **PUT** `/posts/{id}`
- **Description**: Update a post (only by author)
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "title": "Updated Title",
    "body": "Updated body"
  }
  ```
- **Response** (200):
  ```json
  {
    "message": "Post updated successfully",
    "post": {...}
  }
  ```
- **Error** (403): Unauthorized if not the author

#### Delete Post
- **DELETE** `/posts/{id}`
- **Description**: Delete a post (only by author)
- **Auth Required**: Yes
- **Response** (200):
  ```json
  {
    "message": "Post deleted successfully"
  }
  ```
- **Error** (403): Unauthorized if not the author

#### Update Post Tags
- **PUT** `/posts/{id}/tags`
- **Description**: Update tags for a post (only by author)
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "tags": ["laravel", "php", "newtag"]
  }
  ```
- **Response** (200):
  ```json
  {
    "message": "Tags updated successfully",
    "tags": [...]
  }
  ```

---

### Comments

#### Get Post Comments
- **GET** `/posts/{postId}/comments`
- **Description**: Get all comments for a post
- **Auth Required**: Yes
- **Response** (200):
  ```json
  [
    {
      "id": 1,
      "body": "Comment text",
      "post_id": 1,
      "user_id": 1,
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
  ```

#### Create Comment
- **POST** `/posts/{postId}/comments`
- **Description**: Add a comment to a post
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "body": "This is a comment"
  }
  ```
- **Response** (201):
  ```json
  {
    "message": "Comment created successfully",
    "comment": {...}
  }
  ```

#### Update Comment
- **PUT** `/comments/{id}`
- **Description**: Update a comment (only by author)
- **Auth Required**: Yes
- **Request Body**:
  ```json
  {
    "body": "Updated comment"
  }
  ```
- **Response** (200):
  ```json
  {
    "message": "Comment updated successfully",
    "comment": {...}
  }
  ```
- **Error** (403): Unauthorized if not the author

#### Delete Comment
- **DELETE** `/comments/{id}`
- **Description**: Delete a comment (only by author)
- **Auth Required**: Yes
- **Response** (200):
  ```json
  {
    "message": "Comment deleted successfully"
  }
  ```
- **Error** (403): Unauthorized if not the author

---

### Tags

#### Get All Tags
- **GET** `/tags`
- **Description**: Get all available tags
- **Auth Required**: Yes
- **Response** (200):
  ```json
  [
    {"id": 1, "name": "laravel"},
    {"id": 2, "name": "php"},
    {"id": 3, "name": "react"}
  ]
  ```

---

## Error Responses

### 400 Bad Request
```json
{
  "error": "Error message"
}
```

### 401 Unauthorized
```json
{
  "error": "Unauthorized"
}
```

### 403 Forbidden
```json
{
  "error": "Unauthorized"
}
```

### 404 Not Found
```json
{
  "error": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "errors": {
    "field_name": ["Error message"]
  }
}
```

---

## Post Expiry

Posts are automatically deleted 24 hours after creation. The expiry time is calculated from the `created_at` timestamp. The API returns:
- `expires_at`: The exact expiration timestamp
- `is_expired`: Boolean indicating if the post has expired

A scheduled job runs every minute to delete expired posts.

---

## Notes

1. All timestamps are in ISO 8601 format (UTC)
2. Image uploads are stored in `storage/app/public/users/`
3. Soft deletes are used for posts, comments, and tags
4. JWT tokens expire after 60 minutes (configurable)
5. Posts must have at least one tag
6. Users can only edit/delete their own posts and comments

