import {api} from "./client";
import {EventAllowedEmail, GenericDataResponse, GenericPaginatedResponse, IdParam, QueryFilters} from "../types";
import {queryParamsHelper} from "../utilites/queryParamsHelper.ts";

export const eventAllowedEmailsClient = {
    all: async (eventId: IdParam, pagination: QueryFilters) => {
        const response = await api.get<GenericPaginatedResponse<EventAllowedEmail>>(
            `events/${eventId}/allowed-emails` + queryParamsHelper.buildQueryString(pagination),
        );
        return response.data;
    },

    create: async (eventId: IdParam, emails: string[]) => {
        const response = await api.post<GenericDataResponse<EventAllowedEmail[]>>(
            `events/${eventId}/allowed-emails`,
            {emails},
        );
        return response.data;
    },

    delete: async (eventId: IdParam, allowedEmailId: IdParam) => {
        const response = await api.delete(
            `events/${eventId}/allowed-emails/${allowedEmailId}`,
        );
        return response.data;
    },
};
