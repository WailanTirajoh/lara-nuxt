import type {
  ChannelResponse,
  ChannelStoreResponse,
  ChannelShowResponse,
  ChannelDeleteResponse,
  ChannelStoreRequest,
  ChannelUpdateRequest,
  Channel,
} from "@/types/api/channel";

export const useChannelStore = defineStore("channel", () => {
  const channels = ref<Array<Channel>>([]);

  const fetchChannels = async () => {
    const { data } = await get();
    channels.value = data.channels;
  };

  const get = () => {
    return $useFetchAPI<ApiResponse<ChannelResponse>>("/channels");
  };

  const store = (body: ChannelStoreRequest) => {
    return useFetchAPI<ApiResponse<ChannelStoreResponse>>("/channels", {
      method: "post",
      body,
    });
  };

  const show = (id: string) => {
    return $useFetchAPI<ApiResponse<ChannelShowResponse>>(
      `/channels/${id}`,
      {
        method: "get",
      }
    );
  };

  const update = (id: string, body: ChannelUpdateRequest) => {
    return useFetchAPI<ApiResponse<ChannelStoreResponse>>(
      `/channels/${id}`,
      {
        method: "put",
        body,
      }
    );
  };

  const destroy = (id: number) => {
    return $useFetchAPI<ApiResponse<ChannelDeleteResponse>>(
      `/channels/${id.toString()}`,
      {
        method: "delete",
      }
    );
  };
  return { channels, fetchChannels, get, store, show, update, destroy };
});
