<template>
    <header class="header">
      <nav class="navigation">
        <div class="links-block">
          <div class="links">
            <div><router-link to="/">Home</router-link></div>  
          </div>
        </div>
        
        <div class="log">
            <div v-if="store.getters.isAuthenticated">
                <button @click="logout">
                  <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    <span>Logout</span>
                </button>
            </div>
            <div v-else><router-link to="/login" class="logout-block"> 
              <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M11 14C7.13401 14 4 17.134 4 21H11M14.8086 19.7053L19.127 16.3467M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7ZM20 18C20 19.6569 18.6569 21 17 21C15.3431 21 14 19.6569 14 18C14 16.3431 15.3431 15 17 15C18.6569 15 20 16.3431 20 18Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                Login</router-link></div>
        </div>
      </nav>
 
    </header>
  </template>
  <script setup>
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  
  const store = useStore();
  const router = useRouter();
  
//   const isLoggedIn = store.state.isLoggedIn;
  
  const logout = () => {
    store.commit('logout');
    router.push('/login');
  };
  </script>
  
  <style scoped>
  .logout-block {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: white;
    font-family: sans-serif;
    align-items: center;
  }
  .links-block {
    width: 60%;
    display: flex;
  }
    /* Add your header styles here */
    .header {
      background-color: rgb(167, 194, 199);
    }
    .links {
      width: 35%;
      display: flex;
      align-items: center;
      justify-content: space-evenly;
    }
    .links a {
      text-decoration: none;
      color: white;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .navigation {
        display:flex;
        justify-content: flex-end;
    }
    .log button{
      border: none;
      display: flex;
      background: none;
      flex-direction: column;
      align-items: center;
    }
  </style>