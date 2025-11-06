export function subscribeToGroupMessages(groupId, onMessage) {
    if (!window.Echo) {
        console.error('Echo is not initialized');
        return () => {};
    }

    const channel = window.Echo.private(`groups.${groupId}`)
        .listen('.MessageSent', (e) => {
            if (typeof onMessage === 'function') {
                onMessage(e);
            }
        });

    // Unsubscribe function
    return () => {
        try {
            window.Echo.leave(`private-groups.${groupId}`);
        } catch (e) {
            // noop
        }
    };
}


