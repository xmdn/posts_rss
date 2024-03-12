<script setup>
import {ref, onMounted, watchEffect} from 'vue'
import axios from 'axios'
import {useRoute} from "vue-router";
import Quill from 'quill';

import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";

const route = useRoute()
const post = ref(null)

const editorRef = ref(null)

const options = {
  theme: 'snow' // or 'bubble' for bubble theme
}

onMounted(async () => {
  editorRef.value = new Quill('#editor', options)
  try {
    console.log(route.params.id);
    const postId = route.params.id
    const response = await axios.get(`http://192.168.31.122:8003/api/post/${postId}`)
    post.value = response.data
    console.log(response.data)
  } catch (error) {
    console.error('Error fetching post:', error)
  }
})
const saveContent = () => {
  // Get HTML content from Quill editor
  const htmlContent = quillInstance.root.innerHTML;
  // Send the content to your backend or perform other actions
  console.log('Saving content:', htmlContent);
};
watchEffect(() => {
  if (editorRef.value && post.value) {
    // Set the initial content of the Quill editor to post.content
    editorRef.value.root.innerHTML = post.value.content
  }
})
</script>


<template>
  <div id="editor" style="height: 400px;"></div>
  <button @click="saveContent">Save</button>
  <div v-if="post" class="post">
    <img :src="post.thumbnail" alt="Thumbnail">
    <h2>{{ post.title }}</h2>
    <p>Category: {{ post.category }}</p>
    <p>Description: {{ post.description }}</p>
    <p>Published Date: {{ post.pubDate }}</p>
    <p>Author: {{ post.author }}</p>
    <div class="post-content" v-html="post.content"></div>
  </div>
  <div v-else>
    <h3>Loading...</h3>
  </div>
</template>

<style scoped>
.post {
  border: 1px solid #ccc;
  padding: 10px;
}

.post-content {
  margin-top: 20px;
}
.post img {
  width: auto;
  max-width: 60%; /* Adjust the maximum width as needed */
  height: auto;
  max-height: 60vh; /* Adjust the maximum height as needed */
}
</style>