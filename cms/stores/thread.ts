import type {
  ThreadResponse,
  ThreadStoreResponse,
  ThreadShowResponse,
  ThreadDeleteResponse,
  ThreadStoreRequest,
  ThreadUpdateRequest,
  Thread,
} from "@/types/api/thread";

export const useThreadStore = defineStore("thread", () => {
  const get = (channelId: string, options) => {
    return $useFetchAPI<ApiResponse<ThreadResponse>>(
      `/channels/${channelId}/threads`,
      options
    );
  };

  const store = (channelId: string, body: ThreadStoreRequest) => {
    return useFetchAPI<ApiResponse<ThreadStoreResponse>>(
      `/channels/${channelId}/threads`,
      {
        method: "post",
        body,
      }
    );
  };

  const show = (channelId: string, id: string) => {
    return $useFetchAPI<ApiResponse<ThreadShowResponse>>(
      `/channels/${channelId}/threads/${id}`,
      {
        method: "get",
      }
    );
  };

  const update = (channelId: string, id: string, body: ThreadUpdateRequest) => {
    return $useFetchAPI<ApiResponse<ThreadStoreResponse>>(
      `/channels/${channelId}/threads/${id}`,
      {
        method: "put",
        body,
      }
    );
  };

  const destroy = (channelId: string, id: number) => {
    return $useFetchAPI<ApiResponse<ThreadDeleteResponse>>(
      `/channels/${channelId}/threads/${id.toString()}`,
      {
        method: "delete",
      }
    );
  };

  const selectedThread = ref<Thread>();
  const chooseThread = (thread: Thread) => {
    selectedThread.value = thread;
  };

  return {
    selectedThread,
    chooseThread,
    get,
    store,
    show,
    update,
    destroy,
  };
});
