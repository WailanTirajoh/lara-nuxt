export interface ThreadReply {
  id: number;
  body: string;
  created_at: string;
  updated_at: string;
}

export interface ThreadReplyRequest {}
export interface ThreadReplyResponse {
  replies: Array<ThreadReply>;
}

export interface ThreadReplyStoreRequest {
  body: string;
}
export interface ThreadReplyStoreResponse {
  reply: ThreadReply;
}

export interface ThreadReplyShowRequest {}
export interface ThreadReplyShowResponse {
  reply: ThreadReply;
}

export interface ThreadReplyUpdateRequest {
  body: string;
}
export interface ThreadReplyUpdateResponse {
  reply: ThreadReply;
}

export interface ThreadReplyDeleteResponse {}

export interface ThreadReplyRestoreResponse {}
