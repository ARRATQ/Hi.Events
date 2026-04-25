import {useQuery} from "@tanstack/react-query";
import {IdParam, QueryFilters} from "../types.ts";
import {eventAllowedEmailsClient} from "../api/event-allowed-emails.client.ts";

export const GET_EVENT_ALLOWED_EMAILS_QUERY_KEY = 'getEventAllowedEmails';

export const useGetEventAllowedEmails = (eventId: IdParam, pagination: QueryFilters) => {
    return useQuery({
        queryKey: [GET_EVENT_ALLOWED_EMAILS_QUERY_KEY, eventId, pagination],
        queryFn: async () => {
            return await eventAllowedEmailsClient.all(eventId, pagination);
        },
    });
};
