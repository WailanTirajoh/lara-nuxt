export interface Role {
  id: number;
  name: string;
  guard_name: string;
  permissions: Array<string>;
  created_at: string;
  updated_at: string;
}

export interface RoleRequest {
  limit: number;
  page: number;
  query: string;
}
export interface RoleResponse {
  roles: Array<Role>;
}

export interface RoleStoreRequest {
  name: string;
  email: string;
  password: string;
}
export interface RoleStoreResponse {
  role: Role;
}

export interface RoleUpdateRequest {
  name: string;
  email: string;
}
export interface RoleUpdateResponse {
  role: Role;
}

export interface RoleDeleteResponse {}

export interface RoleRestoreResponse {}
