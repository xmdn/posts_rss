<template>
  <div>
    <div class="post-block">
      <div class="context-block">
        <div class="editor-block">
          <span>Title</span>
          <div id="title" class="title"></div>
        </div>
        <div class="editor-block">
          <span>Description</span>
          <div id="description" class="description"></div>
        </div>
        <div class="selector-block">
          <button class="btn-save" @click="saveChanges">Save</button>
          <div>
            <span>Category: </span>
            <select v-model="selectedCategory">
              <option v-for="category in categories" :key="category.id" :value="category">{{ category }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="image-block">
        <span>Image</span>
        <div class="post-img">
          <img :src="post.thumbnail" alt="" class="image">
        </div>
        <div>
          <h4>Or choose new image</h4>
          <input id="imageInput" type="file" @change="handleFileChange">
        </div>
      </div>
    </div>
    
    <div class="editor-block">
      <h3>Content</h3>
      <div id="content" class="content"></div>
    </div>
    
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import Quill from 'quill'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex';


const store = useStore();


const postsTypes = ['title', 'description', 'content'];

const router = useRouter();
const postId = router.currentRoute.value.params.id;

// Method to handle file change event
const handleFileChange = (event) => {
  const file = event.target.files[0];
  console.log(file);
  // Assuming you have a method to handle file upload and set post.thumbnail
  handleImageUpload(file);
};

// Method to handle image upload
const handleImageUpload = (file) => {
  // You can implement your logic for handling the image upload here
  // For example, using FileReader to read the file and update post.thumbnail
  const reader = new FileReader();
  reader.onload = (e) => {
    const imageData = e.target.result;
    // Set the post.thumbnail to the base64 data of the uploaded image
    post.thumbnail = imageData;
  };
  reader.readAsDataURL(file);
};

const editors = {};

const initQuillEditors = (type, index) => {
  console.log(`#${type}`, type, index)
  editors[`${type}Editor`] = new Quill(`#${type}`, {
    theme: 'snow',
    placeholder: 'Enter title here...'
  });
  editors[`${type}Editor`].setContents(editors[`${type}Editor`].clipboard.convert(post.value[type]));
};

const initAll = () => {
  console.log('list ', postsTypes);
  postsTypes.forEach((t, index) => {
    console.log('type ', t);
      initQuillEditors(t, index)
  });
  
}

const selectedCategory = ref('');

const categories = ref([]);

const post = ref({})

onMounted(async () => {
  try {
    const response = await axios.get(`${publicEnvVar}/api/post/${postId}`);
    const postData = response.data; // Assuming the response contains post data

    const category = await axios.get(`${publicEnvVar}/api/categories`);
    categories.value = category.data;
    

    // Set selected category based on post.category
    selectedCategory.value = postData.category;
    console.log('CAt ', category, 'SELECTED ', postData.category);
    
    post.value = postData;
    // defineProps(['pos']);
    console.log('VALUES ', post.value.title);
  } catch (error) {
    console.error('Error fetching post:', error);
  }

  initAll();
  console.log('Edt Arr', editors)
});
// Function to handle editing
const editField = (item) => {
  const post = posts.value.find(post => post.type === item.type)

  console.log('POSTS ', posts, post, item);
  // Toggle the edit property
  post.edit = !post.edit

  
  console.log('Edited post:', post)
}

const saveChanges = async () => {
  const formData = new FormData();
  formData.append('category', 'NewCategotry');

  for (const type of postsTypes) {
    const editor = editors[`${type}Editor`];
    const content = editor.root.innerHTML; // Get the HTML content
    formData.append(type, content);
    // console.log(type, "TYPE == ", content); // Log the HTML content
  }

  // Add the image file to the form data
  const imageFile = document.getElementById('imageInput').files[0];
  if(imageFile) {
    formData.append('image', imageFile);
  }
  console.log('FORM DATA ', post.value);

  try {
    const response = await axios.post(`${publicEnvVar}/api/update/${postId}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': 'Bearer ' + store.state.accessToken
      }
    });
    console.log('Post updated:', response.data);
  } catch (error) {
    console.error('Error updating post:', error);
  }
};

</script>
<style>
span {
  font-weight: bold;
  font-family: "Gill Sans", sans-serif;
}

.btn-save {
  font-size: medium;
  color: #ffffff;
  border-radius: 6px;
  background-color: #5838fa;
  padding: 6px 30px;
}

.selector-block {
  display: flex;
  justify-content: space-evenly;
  padding: 60px 0 0px 0;
}

.context-block {
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  padding: 20px 0;
  border: 1px solid #c5c4c4;
  border-radius: 4px;
  width: 50%;
}

.post-block {
  display: flex;
}

.post-img {
  width: 80%;
}

.image {
  width: -webkit-fill-available;
}

.editor-block {
  padding: 40px 0;
  text-align: center;
}

.image-block {
  padding: 10px 0 30px 0;
  border: 1px solid #c5c4c4;
  border-radius: 4px;
  width: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>