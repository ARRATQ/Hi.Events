import {useQuery} from "@tanstack/react-query";
import {IdParam} from "../types.ts";
import {eventAllowedEmailsClient} from "../api/event-allowed-emails.client.ts";

export const GET_EVENT_ALLOWED_EMAILS_STATS_QUERY_KEY = 'getEventAllowedEmailsStats';

export const useGetEventAllowedEmailsStats = (eventId: IdParam) => {
    return useQuery({
        queryKey: [GET_EVENT_ALLOWED_EMAILS_STATS_QUERY_KEY, eventId],
        queryFn: async () => {
            const res = await eventAllowedEmailsClient.stats(eventId);
            return res.data;
        },
    });
};
