<template>
  <div class="main-block">
    <div class="admin-menu">
      <div class="menu-block">
        <div class="search-block">
          <input v-model="searchInput" placeholder="Search..">
          <button @click="searchCall">Search</button>
        </div>
        <div class="filter-block">
          <div>
            <span>Order: </span>
            <select v-model="order">
              <option value="newest">Newest</option>
              <option value="oldest">Oldest</option>
            </select>
          </div>
          <div>
            <span>Category: </span>
            <select v-model="selectedCategory">
              <option value="no">No category</option>
              <option v-for="category in categories" :key="category.id" :value="category">{{ category }}</option>
            </select>
          </div>
        </div>
        <div v-if="store.getters.isAuthenticated" class="create-block">
          <div>
            <button class="btn-create" @click="goToCreatePost">
              Create New Post
            </button>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="pag-block">
          <div class="pagination">
            <button @click="prevPage" :disabled="page <= 1">
              <svg fill="#000000" width="25px" height="25px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M23.505 0c0.271 0 0.549 0.107 0.757 0.316 0.417 0.417 0.417 1.098 0 1.515l-14.258 14.264 14.050 14.050c0.417 0.417 0.417 1.098 0 1.515s-1.098 0.417-1.515 0l-14.807-14.807c-0.417-0.417-0.417-1.098 0-1.515l15.015-15.022c0.208-0.208 0.486-0.316 0.757-0.316z"></path> </g></svg>
            </button>
            <span>{{ page }}</span>
            <button @click="nextPage" :disabled="page >= pagination">
              <svg fill="#000000" width="25px" height="25px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8.489 31.975c-0.271 0-0.549-0.107-0.757-0.316-0.417-0.417-0.417-1.098 0-1.515l14.258-14.264-14.050-14.050c-0.417-0.417-0.417-1.098 0-1.515s1.098-0.417 1.515 0l14.807 14.807c0.417 0.417 0.417 1.098 0 1.515l-15.015 15.022c-0.208 0.208-0.486 0.316-0.757 0.316z"></path> </g></svg>
            </button>
          </div>
        </div>
      <div v-if="posts.length" class="posts-grid">
        
        <div v-for="post in posts" class="post">
          <div v-if="store.getters.isAuthenticated" class="btns-admin">
            <button @click="deletePost(post.id)" class="btn-delete">
              <svg fill="#000000" width="20px" height="20px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M18.8,16l5.5-5.5c0.8-0.8,0.8-2,0-2.8l0,0C24,7.3,23.5,7,23,7c-0.5,0-1,0.2-1.4,0.6L16,13.2l-5.5-5.5 c-0.8-0.8-2.1-0.8-2.8,0C7.3,8,7,8.5,7,9.1s0.2,1,0.6,1.4l5.5,5.5l-5.5,5.5C7.3,21.9,7,22.4,7,23c0,0.5,0.2,1,0.6,1.4 C8,24.8,8.5,25,9,25c0.5,0,1-0.2,1.4-0.6l5.5-5.5l5.5,5.5c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2.1,0-2.8L18.8,16z"></path> </g></svg>          
            </button>
          </div>
          <router-link :key="post.id" :to="{ name: 'post', params: { id: post.id, postId: post.guid  }}" class="post-link">
          <div>
            <img :src="post.thumbnail" alt="Thumbnail">
            <h2>{{ post.title }}</h2>
            <p>Category: {{ post.category }}</p>
            <p>Description: {{ post.description }}</p>
            <p>Published Date: {{ post.pubDate }}</p>
            <p>Author: {{ post.author }}</p>
          </div>
          </router-link>
          <div v-if="store.getters.isAuthenticated" class="btns-admin">
            <button @click="editPost(post.id)" class="btn-edit">
              Edit
            </button>
          </div>
        </div>
        
      </div>
      <div v-else>
        <p>No posts available</p>
      </div>
    </div>
    
  </div>
  
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();

const posts = ref([]);
const pagination = ref({});
const props = defineProps(['posts']);

const selectedCategory = ref('');
const searchInput = ref('');
const page = ref(1); // Default page number
const order = ref('newest'); // Default order
const categories = ref([]);

// Load search and filter params from local storage
const savedParams = JSON.parse(localStorage.getItem('searchParams'));
const savedSearchInput = savedParams ? savedParams.searchInput : '';
const savedCategory = savedParams ? savedParams.category : '';
const savedOrder = savedParams ? savedParams.order : 'newest';
const savedPage = savedParams ? savedParams.page : 1;
const perPage = 12; // Number of posts per page

// Initialize search and filter params with saved values or defaults
searchInput.value = savedSearchInput;
selectedCategory.value = savedCategory;
order.value = savedOrder;
page.value = savedPage;

// Watch for changes in search and filter params and update local storage
watch([searchInput, selectedCategory, order], ([newSearchInput, newCategory, newOrder]) => {
  localStorage.setItem('searchParams', JSON.stringify({
    searchInput: newSearchInput,
    category: newCategory,
    order: newOrder
  }));
  page.value = 1; // Reset to first page
  loadPosts();
});

// Watch for changes in order and reload the page when it changes
watch(order, () => {
  // Reload the page
  loadPosts();
});

// Watch for changes in selectedCategory and reload the page when it changes
watch(selectedCategory, () => {
  // Reload the page
  loadPosts();
});

// Function to navigate to the previous page
const prevPage = () => {
  if (page.value > 1) {
    page.value--;
    loadPosts();
  }
}

// Function to navigate to the next page
const nextPage = () => {
  if (page.value < pagination.value) {
    page.value++;
    loadPosts();
  }
}

const loadPosts = async () => {
  const publicEnvVar = process.env.API_BASE_URL;
  console.log(process.env.API_BASE_URL)
  console.log(publicEnvVar);
  try {
    // Fetch posts with search, filter, and order params
    const response = await axios.get(`${publicEnvVar}/api/posts`, {
      params: {
        search: searchInput.value,
        category: selectedCategory.value,
        order: order.value,
        page: page.value,
        per_page: perPage
      }
    });

    const category = await axios.get(`${publicEnvVar}/api/categories`);
    categories.value = category.data;

    posts.value = response.data.data
    pagination.value = response.data.last_page
    console.log(response.data);
  } catch (error) {
    console.error('Error fetching posts:', error)
  }
}



onMounted(async () => {
  page.value = 1;
  loadPosts()
  }
)

const goToCreatePost = () => {
  router.push('/post/create');
};

const editPost = (id) => {
      router.push({ name: 'edit', params: { id: id } });
    };

const searchCall = () => {
    location.reload();
  };
    
  const deletePost = (id) => {
    axios.delete(`${publicEnvVar}/api/post/${id}`, {
      headers: {
        'Authorization': 'Bearer ' + store.state.accessToken
      }
    })
      .then(response => {
        // Handle success, e.g., show a success message or update UI
        loadPosts()
        console.log('Post deleted successfully:', response.data);
      })
      .catch(error => {
        // Handle error, e.g., show an error message
        console.error('Error deleting post:', error);
      });
};
</script>



<style scoped>

.btn-create {
  border-radius: 6px;
  padding: 10px 30px;
  background-color: rgb(169, 176, 209);
}

.post-link {
  color: black;
  text-decoration: none;
  flex: 1; /* Allow the link to expand to fill remaining space */
}
.pagination {
  display: flex;
  width: 15%;
  align-items: center;
  justify-content: space-evenly;
}

.pag-block {
  padding-bottom: 10px;
  display: flex;
  justify-content: center;
}

.btn-delete {
  height: 32px;
  border-radius: 80px;
  background-color: rgb(226, 92, 92);
  border: none;
}

.btn-edit {
  width: 72px;
  height: 32px;
  border-radius: 8px;
  background-color: rgb(92, 117, 226);
}

.btns-admin {
  padding-bottom: 14px;
  display: flex;
  justify-content: flex-end;
}

.main-block {
  display: flex;
}

.search-block {
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 3px;
  height: 25%;
  border: 1px solid #c5c4c4;
  margin: 3px;
}

.create-block {
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 3px;
  height: 15%;
  border: 1px solid #c5c4c4;
  margin: 3px;
}

.filter-block {
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  align-items: center;
  border-radius: 3px;
  height: 35%;
  border: 1px solid #c5c4c4;
  margin: 3px;
}

.menu-block {
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  border: 1px solid #c5c4c4;
  position: sticky;
  margin: 5px;
  top: 79px;
  height: 650px;
  border-radius: 5px;
}
.admin-menu {
  min-width: 300px;
  width: 350px;
}

.read-the-docs {
  color: #888;
}
ul.post-content li {
  list-style-type: none !important;
}
ul {
  list-style-type: none !important;
}
p {
  list-style-type: none !important;
}
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Adjust column width as needed */
  gap: 20px; /* Adjust gap between grid items */
}

.post {
  border: 1px solid #ccc;
  padding: 10px;
}
.post img {
  width: auto;
  max-width: 95%; /* Adjust the maximum width as needed */
  height: auto;
  max-height: 95vh; /* Adjust the maximum height as needed */
}
</style>
