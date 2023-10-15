import type { NotificationResponse } from "~/types/api/notification";

export const useNotificationStore = defineStore("notification", () => {
  const notifications = ref([]);

  const fetchNotifications = async () => {
    const { data } = await get();
    notifications.value = data;
  };
  const get = () => {
    return $useFetchAPI<ApiResponse<NotificationResponse>>(
      "/auth/notifications"
    );
  };

  return { notifications, fetchNotifications, get };
});
