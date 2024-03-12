import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
  state: {
    accessToken: localStorage.getItem('accessToken') || null,
    isLoggedIn: !!localStorage.getItem('accessToken'),
  },
  mutations: {
    setAccessToken(state, token) {
      state.accessToken = token;
      state.isLoggedIn = true;
      localStorage.setItem('accessToken', token); // Store the token in localStorage
    },
    clearAccessToken(state) {
      state.accessToken = null;
      state.isLoggedIn = false;
      localStorage.removeItem('accessToken'); // Remove the token from localStorage
    },
    logout(state) {
      state.accessToken = null;
      state.isLoggedIn = false;
      localStorage.removeItem('accessToken'); // Remove the token from localStorage
    },
  },
  actions: {
    login({ commit }, { email, password }) {
      return axios.post('http://192.168.31.122:8003/api/login', { email, password })
        .then(response => {
          const accessToken = response.data.access_token;
          commit('setAccessToken', accessToken);
          return Promise.resolve(response);
        })
        .catch(error => {
          return Promise.reject(error);
        });
    },
    logout({ commit }) {
      return axios.post('http://192.168.31.122:8003/api/logout')
        .then(response => {
          commit('clearAccessToken');
        })
        .catch(error => {
          return Promise.reject(error);
        });
      
    },
    register({ commit }, { name, email, password }) {
      return axios.post('http://192.168.31.122:8003/api/register', { name, email, password })
        .then(response => {
          const accessToken = response.data.access_token;
          commit('setAccessToken', accessToken);
          return Promise.resolve(response);
        })
        .catch(error => {
          return Promise.reject(error);
        });
    },
  },
  getters: {
    isAuthenticated(state) {
      return state.isLoggedIn;
    },
  },
});

export default store;
