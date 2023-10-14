import type {
  UserResponse,
  UserStoreResponse,
  UserDeleteResponse,
  UserRestoreResponse,
  UserStoreRequest,
  UserRequest,
} from "@/types/api/user";

export const useUserStore = defineStore("user", () => {
  const get = (params: Ref<UserRequest>) => {
    return useFetchAPI<ApiResponse<UserResponse>>("/users", {
      params,
    });
  };

  const $get = (params: UserRequest) => {
    return $useFetchAPI<ApiResponse<UserResponse>>("/users", {
      params,
    });
  };

  const store = (body: UserStoreRequest) => {
    return useFetchAPI<ApiResponse<UserStoreResponse>>("/users", {
      method: "post",
      body,
    });
  };

  const update = (id: number, body: FormData) => {
    return useFetchAPI<ApiResponse<UserStoreResponse>>(
      `/users/${id.toString()}`,
      {
        method: "post",
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

  return { $get, get, store, update, restore, destroy, permanentDestroy };
});
