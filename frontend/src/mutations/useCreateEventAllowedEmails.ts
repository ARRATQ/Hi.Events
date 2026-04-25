import {useMutation, useQueryClient} from "@tanstack/react-query";
import {IdParam} from "../types.ts";
import {eventAllowedEmailsClient} from "../api/event-allowed-emails.client.ts";
import {GET_EVENT_ALLOWED_EMAILS_QUERY_KEY} from "../queries/useGetEventAllowedEmails.ts";
import {GET_EVENT_ALLOWED_EMAILS_STATS_QUERY_KEY} from "../queries/useGetEventAllowedEmailsStats.ts";

export const useCreateEventAllowedEmails = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({eventId, emails}: { eventId: IdParam; emails: string[] }) =>
            eventAllowedEmailsClient.create(eventId, emails),

        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({
                queryKey: [GET_EVENT_ALLOWED_EMAILS_QUERY_KEY, variables.eventId],
            });
            queryClient.invalidateQueries({
                queryKey: [GET_EVENT_ALLOWED_EMAILS_STATS_QUERY_KEY, variables.eventId],
            });
        },
    });
};
