export interface ChannelUser {
  id: number;
  name: string;
  email: string;
  profile_picture: string;
}

export interface Channel {
  id: number;
  name: string;
  users: Array<ChannelUser>;
  created_at: string;
  updated_at: string;
}

export interface ChannelRequest {}
export interface ChannelResponse {
  channels: Array<Channel>;
}

export interface ChannelStoreRequest {
  name: string;
}
export interface ChannelStoreResponse {
  channel: Channel;
}

export interface ChannelShowRequest {}
export interface ChannelShowResponse {
  channel: Channel;
}

export interface ChannelUpdateRequest {
  name: string;
}
export interface ChannelUpdateResponse {
  channel: Channel;
}

export interface ChannelDeleteResponse {}

export interface ChannelRestoreResponse {}
