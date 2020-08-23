import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/components/Index'
import Login from '@/components/Login'
import Signup from '@/components/Signup'
import Dashboard from '@/components/Dashboard'
import Tasks from '@/components/Tasks'
import Logout from '@/components/Logout'
import Profile from '@/components/Profile'
import Users from '@/components/Users'
import ShowTask from '@/components/ShowTask'
import EditTask from '@/components/EditTask'
import CreateTask from '@/components/CreateTask'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Index',
      component: Index
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/signup',
      name: 'Signup',
      component: Signup
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/tasks',
      name: 'Tasks',
      component: Tasks
    },
    {
      path: '/tasks/create',
      name: 'CreateTask',
      component: CreateTask
    },
    {
      path: '/tasks/:id',
      name: 'ShowTask',
      component: ShowTask
    },
    {
      path: '/tasks/edit/:id',
      name: 'EditTask',
      component: EditTask
    },
    {
      path: '/logout',
      name: 'Logout',
      component: Logout
    },
    {
      path: '/profile',
      name: 'Profile',
      component: Profile
    },
    {
      path: '/users',
      name: 'Users',
      component: Users
    }
  ]
})