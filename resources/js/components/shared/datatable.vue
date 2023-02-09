<template>
  <table class="table-auto w-full bg-white">
    <thead>
      <tr class="bg-primary text-center">
        <th v-for="(header, index) in headers" :key="index" class="
            sticky
            top-0
            w-1/5
            min-w-[160px]
            text-sm
            font-bold
            text-[#64748B]
            py-4
            lg:py-7
            px-3
            lg:px-4
            border-transparent
            bg-gray-200
            z-20
          ">
          {{ header.name }}
        </th>
        <th v-if="!hideActions" class="
            sticky
            top-0
            w-1/5
            min-w-[160px]
            text-sm
            font-bold
            text-[#64748B]
            py-4
            lg:py-7
            px-3
            lg:px-4
            border-transparent
            bg-gray-200
            z-20
          ">
          Actions
        </th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="(item, index) in data" :key="index">
        <td v-for="(header, index) in headers" :key="index" class="
            text-center text-dark
            font-medium
            text-base
            py-5
            px-2
            bg-white
            border-b border-[#E8E8E8]
          ">
          <img v-if="header.isImage" class="w-8 h-8 mx-auto" :src="item[header.value]" />
          <div v-else-if="header.isBoolean">
            <input type="checkbox" :name="header.value" :checked="item[header.value]" onclick="return false" />
          </div>
          <p v-else>{{ (item[header.value]) ? (item[header.value].length > 20) ? item[header.value].substring(0, 20)+'...' : item[header.value] : "N/A" }}</p>
        </td>
        <td v-if="!hideActions" class="
            relative
            flex flex-col
            items-center
            justify-center
            w-full
            text-center text-dark
            font-medium
            text-base
            py-6
            px-2
            bg-white
            border-b border-[#E8E8E8]
          ">
          <a class="w-[24px] mx-auto" href="#!" @click="toggleAction(index)">
            <img class="w-[24px] mx-auto" src="/img/icons/more.svg" />
          </a>
          <div v-if="showActions[index]" class="absolute top-10 z-10 w-[150px] mt-4 bg-white shadow-md">
            <ul>
              <li>
                <a class="
                    block
                    w-full
                    text-left text-blue-700
                  hover:bg-gray-100
                    px-4
                    py-2
                  " v-if="allowedActions.details" href="#!" @click="detailMethod(index)">Details</a>
              </li>
              <slot name="actions" :index="index"></slot>
              <li>
                <a class="
                    block
                    w-full
                    text-left text-blue-700
                    hover:bg-gray-100
                    px-4
                    py-2
                  " v-if="allowedActions.edit" href="#!" @click="editItem(index)">Edit</a>
              </li>
              <li>
                <a class="
                    block
                    w-full
                    text-left text-red-700
                    hover:bg-gray-100
                    px-4
                    py-2
                  " v-if="allowedActions.delete" href="#!" @click="deleteItem(index)">Delete</a>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script >
export default {
  props: {
    headers: Array,
    data: Array,
    allowedActions: Object,
    hideActions: {
      type: Boolean,
      default: false,
    },
    detailMethod: {
      type: Function,
      required: false,
    },
    editMethod: {
      type: Function,
      required: false,
    },
    deleteMethod: {
      type: Function,
      required: false,
    },
  },
  data() {
    return {
      showActions: Array(),
    };
  },
  methods: {
    loadShowActions(dataLength) {
      this.showActions = new Array(dataLength).fill(false);
    },
    toggleAction(index) {
      const newValue = !this.showActions[index];
      this.showActions = new Array(this.data.length).fill(false);
      this.showActions[index] = newValue;
    },
    clickOutside(index) {
      console.log("Index", index);
    },
    editItem(index) {
      const itemId = this.data[index].id;
      this.editMethod(itemId, index);
    },
    deleteItem(index) {
      const itemId = this.data[index].id;
      this.deleteMethod(itemId, index);
    },
  },
  watch: {
    // Refresh the showActions list on dialog confirmation
    data(newValue, oldValue) {
      this.loadShowActions(newValue.length);
    },
  },
  mounted() {
    if (this.data) {
      this.loadShowActions(this.data.length);
    }
  },
};
</script>