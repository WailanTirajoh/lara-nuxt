import { format, parseISO } from "date-fns";

export const stringToSlug = (str: string) => {
  return str
    .replace(/[^\w\s-]/g, "")
    .toLowerCase()
    .replace(/\s+/g, "-");
};

export const formatDatetime = (
  datetime: string,
  datetimeFormat = "MM/dd/yyyy HH:mm:ss"
) => {
  return format(parseISO(datetime), datetimeFormat);
};

export const formatDate = (datetime: string) => {
  return format(parseISO(datetime), "MM/dd/yyyy");
};
