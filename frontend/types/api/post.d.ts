export interface Post {
  id: number;
  title: string;
  slug: string;
  body: string;
  tags: string;
  author: {
    id: number;
    name: string;
  };
  created_at: string;
  updated_at: string;
}

export interface PostRequest {
  limit: number;
  page: number;
  with_trashed: boolean | undefined;
  query: string;
}
export interface PostResponse {
  posts: Array<Post>;
}

export interface PostStoreRequest {
  title: string;
  slug: string;
  body: string;
}
export interface PostStoreResponse {
  post: Post;
}

export interface PostUpdateRequest {
  title: string;
  slug: string;
  body: string;
}
export interface PostUpdateResponse {
  post: Post;
}

export interface PostDeleteResponse {}

export interface PostRestoreResponse {}
