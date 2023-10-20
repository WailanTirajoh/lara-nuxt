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
  const threads = ref<Array<Thread>>([]);

  const fetchThreads = async (channelId: string) => {
    const { data } = await get(channelId);
    threads.value = data.threads;
  };

  const get = (channelId: string) => {
    return $useFetchAPI<ApiResponse<ThreadResponse>>(
      `/channels/${channelId}/threads`
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
    threads,
    selectedThread,
    chooseThread,
    fetchThreads,
    get,
    store,
    show,
    update,
    destroy,
  };
});
