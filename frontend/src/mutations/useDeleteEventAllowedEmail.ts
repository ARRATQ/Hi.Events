import {useMutation, useQueryClient} from "@tanstack/react-query";
import {IdParam} from "../types.ts";
import {eventAllowedEmailsClient} from "../api/event-allowed-emails.client.ts";
import {GET_EVENT_ALLOWED_EMAILS_QUERY_KEY} from "../queries/useGetEventAllowedEmails.ts";

export const useDeleteEventAllowedEmail = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({eventId, allowedEmailId}: { eventId: IdParam; allowedEmailId: IdParam }) =>
            eventAllowedEmailsClient.delete(eventId, allowedEmailId),

        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({
                queryKey: [GET_EVENT_ALLOWED_EMAILS_QUERY_KEY, variables.eventId],
            });
        },
    });
};
