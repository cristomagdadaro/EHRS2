<script setup>
import { onMounted, ref } from 'vue';
import InputField from '@/Components/Generic/Forms/InputField.vue';

defineProps({
    modelValue: [String, Number],
    errorMsg: [String, Array],
    id: String,
    name: String,
    label: String,
    step: Number,
    autofocus: Boolean,
    required: Boolean,
    type: {
        type: String,
        default: 'text',
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>
<style>
.inputText{
    @apply sm:p-2 p-1 w-full border overflow-ellipsis rounded-md shadow-sm focus:border-vsu-olive focus:ring focus:ring-indigo-200 focus:ring-opacity-50 duration-300;
}
</style>
<template>
    <InputField :name="name" :errorMsg="errorMsg" :label="label" :required="required">
    <textarea
        :id="id"
        :name="name"
        ref="input"
        :autofocus="autofocus"
        class="inputText"
        :class="errorMsg ? 'border-red-300' : 'border-gray-300'"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
    </InputField>

</template>
