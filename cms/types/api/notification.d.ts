import { Thread } from "./thread";
import { ThreadReply } from "./thread-reply";

export interface Notification {
  id: string;
  type: string;
  data: NotificationData;
  read_at: string | null;
  created_at: string;
}

export interface NotificationData {
  info: string;
  data: NotificationDetail;
}

export interface NotificationDetail {
  thread: Thread;
  reply: ThreadReply;
}
