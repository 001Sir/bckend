<template>
  <div clas="w-64 dropdown relative">
    <a id="dropdownMenuButton2" type="button" href="#!" data-bs-toggle="dropdown" aria-expanded="false" class="
        w-48
        dropdown-toggle
        px-6
        py-4
        border
        border-gray-300
        bg-white
        text-black
        font-bold
        text-sm
        leading-tight
        uppercase
        rounded
        hover:bg-gray-200
        transition
        duration-150
        ease-in-out
        flex
        items-center
        whitespace-nowrap
      " @click="toggleVisibility()">
      <p>Filter ({{ label }})</p>
    </a>
    <ul v-show="showOptions" class="
        w-48
        dropdown-menu
        min-w-max
        absolute
        bg-white
        text-base
        z-50
        float-left
        py-2
        list-none
        text-left
        rounded-lg
        shadow-lg
        mt-1
        m-0
        bg-clip-padding
        border-none
      " aria-labelledby="dropdownMenuButton2">
      <li v-for="(list, index) in values" :key="index">
        <a class="
            dropdown-item
            text-sm
            py-2
            px-4
            block
            w-full
            whitespace-nowrap
            bg-transparent
            cursor-pointer
            text-gray-700
            hover:bg-gray-100
          " :class="(current == list.value) ? 'font-bold' : 'font-normal'" @click="change(list.value)">{{ list.name }}
        </a>
        <hr v-if="index != (values.length - 1)" class="
              h-0
              mx-4
              my-2
              border border-solid border-t-0 border-gray-400
              opacity-25
            " />
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  emits: ['changeFilter'],
  props: ['current', 'values'],
  data() {
    return {
      label: 'Sort',
      showOptions: false,
    };
  },
  mounted() {
    this.label = this.values[this.values.findIndex(object => {
        return object.value === this.current;
      })].name
  },
  watch: {
    current(newValue, oldValue) {
      this.label = this.values[this.values.findIndex(object => {
        return object.value === newValue;
      })].name;
    }
  },
  methods: {
    toggleVisibility() {
      this.showOptions = !this.showOptions;
    },
    change(value) {
      this.$emit('changeFilter', value);
      this.showOptions = false;
    }
  },
}
</script>