import type {
  PostResponse,
  PostStoreResponse,
  PostDeleteResponse,
  PostRestoreResponse,
  PostStoreRequest,
  PostUpdateRequest,
  PostRequest,
} from "@/types/api/post";

export const usePostStore = defineStore("post", () => {
  const get = (params: Ref<PostRequest>) => {
    return useFetchAPI<ApiResponse<PostResponse>>("/posts", {
      params,
    });
  };

  const store = (body: PostStoreRequest) => {
    return useFetchAPI<ApiResponse<PostStoreResponse>>("/posts", {
      method: "post",
      body,
    });
  };

  const update = (id: number, body: PostUpdateRequest) => {
    return useFetchAPI<ApiResponse<PostStoreResponse>>(`/posts/${id.toString()}`, {
      method: "put",
      body,
    });
  };

  const destroy = (id: number) => {
    return $useFetchAPI<ApiResponse<PostDeleteResponse>>(`/posts/${id.toString()}`, {
      method: "delete",
    });
  };

  const restore = (id: number) => {
    return $useFetchAPI<ApiResponse<PostRestoreResponse>>(
      `/posts/${id.toString()}/restore`,
      {
        method: "put",
      }
    );
  };

  return { get, store, update, restore, destroy };
});
