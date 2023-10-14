export interface ThreadUser {
  id: number;
  name: string;
  email: string;
  profile_picture: string;
}

export interface Thread {
  id: number;
  body: string;
  user: ThreadUser;
  created_at: string;
  updated_at: string;
}

export interface ThreadRequest {}
export interface ThreadResponse {
  threads: Array<Thread>;
}

export interface ThreadStoreRequest {
  body: string;
}
export interface ThreadStoreResponse {
  thread: Thread;
}

export interface ThreadShowRequest {}
export interface ThreadShowResponse {
  thread: Thread;
}

export interface ThreadUpdateRequest {
  body: string;
}
export interface ThreadUpdateResponse {
  thread: Thread;
}

export interface ThreadDeleteResponse {}

export interface ThreadRestoreResponse {}
