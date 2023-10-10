export interface User {
  id: number;
  name: string;
  email: string;
  profile_picture: string;
  roles: Array<string>;
  created_at: string;
  updated_at: string;
}

export interface UserRequest {
  limit: number;
  page: number;
  query: string;
}
export interface UserResponse {
  users: Array<User>;
}

export interface UserStoreRequest {
  name: string;
  email: string;
  password: string;
  roles: Array<string>;
}
export interface UserStoreResponse {
  user: User;
}

export interface UserUpdateRequest {
  name: string;
  email: string;
  image: any;
  roles: Array<string>;
}
export interface UserUpdateResponse {
  user: User;
}

export interface UserDeleteResponse {}

export interface UserRestoreResponse {}
