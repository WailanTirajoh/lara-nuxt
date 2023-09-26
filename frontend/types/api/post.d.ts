export interface Post {
  id: number;
  title: string;
  slug: string;
  body: string;
  author: {
    id: number;
    name: string;
  };
  created_at: string;
  updated_at: string;
}

export interface PostResponse {
  posts: Array<Post>;
}

export interface PostStoreResponse {
  post: Post;
}

export interface PostDeleteResponse {}
