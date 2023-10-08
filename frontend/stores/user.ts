import type {
  UserResponse,
  UserStoreResponse,
  UserDeleteResponse,
  UserRestoreResponse,
  UserStoreRequest,
  UserUpdateRequest,
  UserRequest,
} from "@/types/api/user";

export const useUserStore = defineStore("user", () => {
  const get = (params: Ref<UserRequest>) => {
    return useFetchAPI<ApiResponse<UserResponse>>("/users", {
      params,
    });
  };

  const store = (body: UserStoreRequest) => {
    return useFetchAPI<ApiResponse<UserStoreResponse>>("/users", {
      method: "post",
      body,
    });
  };

  const update = (id: number, body: UserUpdateRequest) => {
    return useFetchAPI<ApiResponse<UserStoreResponse>>(
      `/users/${id.toString()}`,
      {
        method: "put",
        body,
      }
    );
  };

  const destroy = (id: number) => {
    return $useFetchAPI<ApiResponse<UserDeleteResponse>>(
      `/users/${id.toString()}`,
      {
        method: "delete",
      }
    );
  };

  const restore = (id: number) => {
    return $useFetchAPI<ApiResponse<UserRestoreResponse>>(
      `/users/${id.toString()}/restore`,
      {
        method: "put",
      }
    );
  };

  const permanentDestroy = (id: number) => {
    return $useFetchAPI<ApiResponse<UserRestoreResponse>>(
      `/users/${id.toString()}/destroy-permanent`,
      {
        method: "delete",
      }
    );
  };

  return { get, store, update, restore, destroy, permanentDestroy };
});
