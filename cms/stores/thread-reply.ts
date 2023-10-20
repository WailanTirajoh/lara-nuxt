import type {
  ThreadReplyResponse,
  ThreadReplyStoreResponse,
  ThreadReplyShowResponse,
  ThreadReplyDeleteResponse,
  ThreadReplyStoreRequest,
  ThreadReplyUpdateRequest,
  ThreadReply,
} from "~/types/api/thread-reply";

export const useThreadReplyStore = defineStore("thread-reply", () => {
  const replies = ref<Array<ThreadReply>>([]);

  const fetchThreadReplies = async (threadId: string) => {
    const { data } = await get(threadId);
    replies.value = data.replies;
  };

  const get = (threadId: string) => {
    return $useFetchAPI<ApiResponse<ThreadReplyResponse>>(
      `/threads/${threadId}/replies`
    );
  };

  const store = (threadId: string, body: ThreadReplyStoreRequest) => {
    return $useFetchAPI<ApiResponse<ThreadReplyStoreResponse>>(
      `/threads/${threadId}/replies`,
      {
        method: "post",
        body,
      }
    );
  };

  const show = (threadId: string, id: string) => {
    return $useFetchAPI<ApiResponse<ThreadReplyShowResponse>>(
      `/threads/${threadId}/replies/${id}`,
      {
        method: "get",
      }
    );
  };

  const update = (
    threadId: string,
    id: string,
    body: ThreadReplyUpdateRequest
  ) => {
    return $useFetchAPI<ApiResponse<ThreadReplyStoreResponse>>(
      `/threads/${threadId}/replies/${id}`,
      {
        method: "put",
        body,
      }
    );
  };

  const destroy = (threadId: string, id: number) => {
    return $useFetchAPI<ApiResponse<ThreadReplyDeleteResponse>>(
      `/threads/${threadId}/replies/${id.toString()}`,
      {
        method: "delete",
      }
    );
  };
  return {
    replies,
    fetchThreadReplies,
    get,
    store,
    show,
    update,
    destroy,
  };
});
