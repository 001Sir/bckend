<template>
    <div class="w-full h-[20em] mt-2">
        <div class="flex flex-wrap">
            <button @click.prevent="applyBold" class="button">
                <font-awesome-icon :icon="['fas', 'bold']" />
            </button>
            <button @click.prevent="applyItalic" class="button">
                <font-awesome-icon :icon="['fas', 'italic']" />
            </button>
            <button @click.prevent="toggleUrl" class="button" :class="(showUrlInput) ? 'border-teal-300' : ''">
                <font-awesome-icon :icon="['fas', 'link']" />
            </button>
            <button @click.prevent="applyHeading" class="button">
                <font-awesome-icon :icon="['fas', 'heading']" />
            </button>
            <button @click.prevent="applyUl" class="button">
                <font-awesome-icon :icon="['fas', 'list-ul']" />
            </button>
            <button @click.prevent="applyOl" class="button">
                <font-awesome-icon :icon="['fas', 'list-ol']" />
            </button>
            <button @click.prevent="undo" class="button">
                <font-awesome-icon :icon="['fas', 'undo']" />
            </button>
            <button @click.prevent="redo" class="button">
                <font-awesome-icon :icon="['fas', 'redo']" />
            </button>
        </div>

        <div v-if="showUrlInput">
            <input type="text" name="url" class="border-2 w-[20em] mb-4 py-1 px-2" v-model="url" />
            <button @click.prevent="applyUrl" class="border-2 ml-2 py-1 px-2 rounded-md mb-4">Insert</button>
        </div>

        <div @input="onInput" v-html="innerValue" contenteditable="true"
            class="wysiwyg-output h-full max-h-[20em] bg-white outline-none border-2 p-4 rounded-md focus:border-black" />
    </div>
</template>
  
<script>
export default {
    props: ['modelValue'],
    emits: ['update:modelValue'],
    data() {
        return {
            showUrlInput: '',
            url: '',
            innerValue: this.modelValue || '<p><br/></p>',
        }
    },
    methods: {
        toggleUrl() {
            this.showUrlInput = !this.showUrlInput;
        },
        applyBold() {
            document.execCommand('bold');
        },
        applyItalic() {
            document.execCommand('italic');
        },
        applyUrl() {
            document.execCommand('createLink', false, this.url);
        },
        applyHeading() {
            document.execCommand('formatBlock', false, '<h1>');
        },
        applyUl() {
            document.execCommand('insertUnorderedList');
        },
        applyOl() {
            document.execCommand('insertOrderedList');
        },
        undo() {
            document.execCommand('undo');
        },
        redo() {
            document.execCommand('redo');
        },
        onInput(event) {
            this.$emit('update:modelValue', event.target.innerHTML);
        }
    }
}
</script>
