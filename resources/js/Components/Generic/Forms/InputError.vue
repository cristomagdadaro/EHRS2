<script setup>
defineProps({
    message: [String, Array],
});
</script>
<style>
.inputError{
    @apply text-xs text-red-600 w-fit text-right whitespace-nowrap overflow-hidden overflow-ellipsis;
}
</style>
<template>
    <p class="inputError flex" v-show="message">
        <template v-if="typeof message === 'string'">
            {{ message }}
        </template>
        <span v-else>
            <span v-for="(msg, index) in message" :key="index">
                {{ msg }}
                <span v-if="index < message.length - 1">, </span>
            </span>
        </span>
    </p>
</template>
