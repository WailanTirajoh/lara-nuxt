import type { NotificationResponse } from "~/types/api/notification";

export const useNotificationStore = defineStore("notification", () => {
  const notifications = ref([]);
  const unreadNotificationCount = ref();

  const fetchNotifications = async () => {
    const { data } = await get();
    notifications.value = data;
  };
  const get = () => {
    return $useFetchAPI<ApiResponse<NotificationResponse>>(
      "/auth/notifications"
    );
  };

  const fetchUnreadNotifications = async () => {
    const { data } = await getUnreadNotification();
    unreadNotificationCount.value = data;
  };
  const getUnreadNotification = () => {
    return $useFetchAPI<ApiResponse<NotificationResponse>>(
      "/auth/notifications/unread-count"
    );
  };

  const markAsRead = (id: string) => {
    return $useFetchAPI<ApiResponse<NotificationResponse>>(
      `/auth/notifications/${id}/mark-as-read`,
    );
  };

  return {
    notifications,
    unreadNotificationCount,
    fetchUnreadNotifications,
    fetchNotifications,
    markAsRead,
    get,
  };
});
