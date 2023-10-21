import type {
  Notification,
} from "~/types/api/notification";

export const useNotificationStore = defineStore("notification", () => {
  const notifications = ref<Notification[]>([]);
  const unreadNotificationCount = ref<number>();

  const fetchNotifications = async () => {
    const { data } = await get();
    notifications.value = data;
  };
  const get = () => {
    return $useFetchAPI<ApiResponse<Notification[]>>(
      "/auth/notifications"
    );
  };

  const fetchUnreadNotifications = async () => {
    const { data } = await getUnreadNotification();
    unreadNotificationCount.value = data;
  };
  const getUnreadNotification = () => {
    return $useFetchAPI<ApiResponse<number>>(
      "/auth/notifications/unread-count"
    );
  };

  const markAsRead = (id: string) => {
    return $useFetchAPI<ApiResponse<null>>(
      `/auth/notifications/${id}/mark-as-read`
    );
  };

  const markAllAsRead = () => {
    return $useFetchAPI<ApiResponse<null>>(
      `/auth/notifications/mark-as-read`
    );
  };

  return {
    notifications,
    unreadNotificationCount,
    markAllAsRead,
    fetchUnreadNotifications,
    fetchNotifications,
    markAsRead,
    get,
  };
});
