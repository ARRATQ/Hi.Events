import {t, Trans} from "@lingui/macro";
import {
    ActionIcon,
    Alert,
    Button,
    FileButton,
    Group,
    ScrollArea,
    Stack,
    Switch,
    Table,
    Text,
    TextInput,
    Tooltip,
} from "@mantine/core";
import {useForm} from "@mantine/form";
import {useParams} from "react-router";
import {useEffect, useRef, useState} from "react";
import {IconInfoCircle, IconTrash, IconUpload} from "@tabler/icons-react";
import {Card} from "../../../../../common/Card";
import {HeadingWithDescription} from "../../../../../common/Card/CardHeading";
import {showError, showSuccess} from "../../../../../../utilites/notifications.tsx";
import {useFormErrorResponseHandler} from "../../../../../../hooks/useFormErrorResponseHandler.tsx";
import {useUpdateEventSettings} from "../../../../../../mutations/useUpdateEventSettings.ts";
import {useGetEventSettings} from "../../../../../../queries/useGetEventSettings.ts";
import {useGetEventAllowedEmails} from "../../../../../../queries/useGetEventAllowedEmails.ts";
import {useCreateEventAllowedEmails} from "../../../../../../mutations/useCreateEventAllowedEmails.ts";
import {useDeleteEventAllowedEmail} from "../../../../../../mutations/useDeleteEventAllowedEmail.ts";

export const AllowedEmailsSettings = () => {
    const {eventId} = useParams();
    const eventSettingsQuery = useGetEventSettings(eventId);
    const updateMutation = useUpdateEventSettings();
    const allowedEmailsQuery = useGetEventAllowedEmails(eventId, {perPage: 500, pageNumber: 1});
    const createMutation = useCreateEventAllowedEmails();
    const deleteMutation = useDeleteEventAllowedEmail();
    const formErrorHandle = useFormErrorResponseHandler();
    const resetCsvRef = useRef<() => void>(null);

    const [emailInput, setEmailInput] = useState('');

    const form = useForm({
        initialValues: {
            allowed_emails_only: false,
        }
    });

    useEffect(() => {
        if (eventSettingsQuery?.isFetched && eventSettingsQuery?.data) {
            form.setValues({
                allowed_emails_only: eventSettingsQuery.data.allowed_emails_only ?? false,
            });
        }
    }, [eventSettingsQuery.isFetched]);

    const handleToggleSubmit = (values: typeof form.values) => {
        updateMutation.mutate({
            eventSettings: values,
            eventId,
        }, {
            onSuccess: () => showSuccess(t`Successfully Updated Settings`),
            onError: (error) => formErrorHandle(form, error),
        });
    };

    const handleAddEmail = () => {
        const trimmed = emailInput.trim().toLowerCase();
        if (!trimmed) return;

        createMutation.mutate({eventId, emails: [trimmed]}, {
            onSuccess: () => {
                setEmailInput('');
                showSuccess(t`Email added`);
            },
            onError: () => showError(t`Failed to add email`),
        });
    };

    const handleCsvUpload = (file: File | null) => {
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            const content = e.target?.result as string;
            const emails = content
                .split(/[\r\n,;]+/)
                .map((s) => s.trim().toLowerCase())
                .filter((s) => s.includes('@'));

            if (emails.length === 0) {
                showError(t`No valid emails found in file`);
                return;
            }

            createMutation.mutate({eventId, emails}, {
                onSuccess: () => showSuccess(t`${emails.length} email(s) imported`),
                onError: () => showError(t`Failed to import emails`),
            });
        };
        reader.readAsText(file);
        resetCsvRef.current?.();
    };

    const handleDelete = (allowedEmailId: number) => {
        deleteMutation.mutate({eventId, allowedEmailId}, {
            onError: () => showError(t`Failed to delete email`),
        });
    };

    const emails = allowedEmailsQuery.data ?? [];

    return (
        <Card>
            <HeadingWithDescription
                heading={t`Guest List Restriction`}
                description={t`Restrict registration to a list of invited email addresses. Only people whose email is on the list will be able to complete checkout.`}
            />

            <form onSubmit={form.onSubmit(handleToggleSubmit)}>
                <fieldset disabled={eventSettingsQuery.isLoading || updateMutation.isPending}>
                    <Switch
                        {...form.getInputProps('allowed_emails_only', {type: 'checkbox'})}
                        label={t`Enable guest list restriction`}
                        description={t`When enabled, only emails on the list below can complete checkout.`}
                    />
                    <Button loading={updateMutation.isPending} type="submit" mt="md">
                        {t`Save`}
                    </Button>
                </fieldset>
            </form>

            {form.values.allowed_emails_only && (
                <>
                    <Alert icon={<IconInfoCircle size={16}/>} color="blue" mt="lg">
                        <Trans>
                            Anyone not on this list will see an error when they try to check out.
                        </Trans>
                    </Alert>

                    <Stack mt="lg" gap="sm">
                        <Text fw={500}><Trans>Add emails</Trans></Text>

                        <Group gap="sm" align="flex-end">
                            <TextInput
                                value={emailInput}
                                onChange={(e) => setEmailInput(e.currentTarget.value)}
                                onKeyDown={(e) => e.key === 'Enter' && (e.preventDefault(), handleAddEmail())}
                                placeholder="email@example.com"
                                style={{flex: 1}}
                            />
                            <Button
                                onClick={handleAddEmail}
                                loading={createMutation.isPending}
                                disabled={!emailInput.trim()}
                            >
                                {t`Add`}
                            </Button>
                            <FileButton resetRef={resetCsvRef} onChange={handleCsvUpload} accept=".csv,.txt">
                                {(props) => (
                                    <Tooltip label={t`Import from CSV or TXT file (one email per line)`}>
                                        <Button variant="outline" leftSection={<IconUpload size={16}/>} {...props}>
                                            {t`Import`}
                                        </Button>
                                    </Tooltip>
                                )}
                            </FileButton>
                        </Group>

                        {emails.length > 0 && (
                            <ScrollArea mah={400} mt="sm">
                                <Table striped withTableBorder>
                                    <Table.Thead>
                                        <Table.Tr>
                                            <Table.Th>{t`Email`}</Table.Th>
                                            <Table.Th style={{width: 60}}/>
                                        </Table.Tr>
                                    </Table.Thead>
                                    <Table.Tbody>
                                        {emails.map((entry) => (
                                            <Table.Tr key={entry.id}>
                                                <Table.Td>{entry.email}</Table.Td>
                                                <Table.Td>
                                                    <ActionIcon
                                                        color="red"
                                                        variant="subtle"
                                                        onClick={() => handleDelete(entry.id!)}
                                                        loading={deleteMutation.isPending}
                                                    >
                                                        <IconTrash size={16}/>
                                                    </ActionIcon>
                                                </Table.Td>
                                            </Table.Tr>
                                        ))}
                                    </Table.Tbody>
                                </Table>
                            </ScrollArea>
                        )}

                        {emails.length === 0 && !allowedEmailsQuery.isLoading && (
                            <Text c="dimmed" size="sm">
                                <Trans>No emails added yet.</Trans>
                            </Text>
                        )}
                    </Stack>
                </>
            )}
        </Card>
    );
};
