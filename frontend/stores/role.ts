import type {
  RoleResponse,
  RoleStoreResponse,
  RoleDeleteResponse,
  RoleRestoreResponse,
  RoleStoreRequest,
  RoleUpdateRequest,
  RoleRequest,
} from "@/types/api/role";

export const useRoleStore = defineStore("role", () => {
  const get = (params: Ref<RoleRequest>) => {
    return useFetchAPI<ApiResponse<RoleResponse>>("/roles", {
      params,
    });
  };

  const store = (body: RoleStoreRequest) => {
    return useFetchAPI<ApiResponse<RoleStoreResponse>>("/roles", {
      method: "post",
      body,
    });
  };

  const update = (id: number, body: RoleUpdateRequest) => {
    return useFetchAPI<ApiResponse<RoleStoreResponse>>(
      `/roles/${id.toString()}`,
      {
        method: "put",
        body,
      }
    );
  };

  const destroy = (id: number) => {
    return $useFetchAPI<ApiResponse<RoleDeleteResponse>>(
      `/roles/${id.toString()}`,
      {
        method: "delete",
      }
    );
  };

  const restore = (id: number) => {
    return $useFetchAPI<ApiResponse<RoleRestoreResponse>>(
      `/roles/${id.toString()}/restore`,
      {
        method: "put",
      }
    );
  };

  const permanentDestroy = (id: number) => {
    return $useFetchAPI<ApiResponse<RoleRestoreResponse>>(
      `/roles/${id.toString()}/destroy-permanent`,
      {
        method: "delete",
      }
    );
  };

  return { get, store, update, restore, destroy, permanentDestroy };
});
