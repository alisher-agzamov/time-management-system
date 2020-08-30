import Vue from 'vue'
import Router from 'vue-router'
import store from "../services/store"

import Index from '@/components/Index'
import Login from '@/components/Login'
import Signup from '@/components/Signup'
import Logout from '@/components/Logout'
import Profile from '@/components/Profile'
import Users from '@/components/Users'
import CreateUser from '@/components/CreateUser'
import EditUser from '@/components/EditUser'
import Tasks from '@/components/Tasks'
import ShowTask from '@/components/ShowTask'
import EditTask from '@/components/EditTask'
import CreateTask from '@/components/CreateTask'
import ShowUser from '@/components/ShowUser'

Vue.use(Router);

const router = new Router({
  routes: [
    { path: '/', name: 'Index', component: Index },
    { path: '/login', name: 'Login', component: Login },
    { path: '/signup', name: 'Signup', component: Signup },
    { path: '/tasks', name: 'Tasks', component: Tasks },
    { path: '/tasks/create', name: 'CreateTask', component: CreateTask },
    { path: '/tasks/:id', name: 'ShowTask', component: ShowTask },
    { path: '/tasks/edit/:id', name: 'EditTask', component: EditTask },
    { path: '/logout', name: 'Logout', component: Logout },
    { path: '/profile', name: 'Profile', component: Profile },
    { path: '/users', name: 'Users', component: Users },
    { path: '/users/create', name: 'CreateUser', component: CreateUser },
    { path: '/users/edit/:id', name: 'EditUser', component: EditUser },
    { path: '/users/:id/tasks', name: 'UserTasks', component: Tasks },
    { path: '/users/:id/tasks/create', name: 'UserTasksCreate', component: CreateTask },
    { path: '/users/:user_id/tasks/:id', name: 'ShowUserTask', component: ShowTask },
    { path: '/users/:user_id/tasks/:id/edit', name: 'EditUserTask', component: EditTask },
    { path: '/users/:id', name: 'ShowUser', component: ShowUser }
  ]
});

router.beforeEach((to, from, next) => {

  if(!['Index', 'Login', 'Signup'].includes(to.name)
    && !store.state.isAuthenticated) {

    return next({ name: 'Login' });
  }

  if(['Login', 'Signup'].includes(to.name)
    && store.state.isAuthenticated) {

    return next({ name: 'Index' });
  }

  if(['Tasks', 'CreateTask', 'ShowTask', 'EditTask'].includes(to.name)
    && !['admin', 'user'].includes(store.state.user.role)) {

    return next({ name: 'Index' });
  }

  if(['UserTasks', 'UserTasksCreate', 'ShowUserTask', 'EditUserTask'].includes(to.name)
    && !['admin'].includes(store.state.user.role)) {

    return next({ name: 'Index' });
  }

  if(['Users', 'CreateUser', 'ShowUser', 'EditUser'].includes(to.name)
    && !['admin', 'manager'].includes(store.state.user.role)) {

    return next({ name: 'Index' });
  }

  if(['ShowTask', 'EditTask', 'ShowUserTask', 'EditUserTask', 'ShowUser', 'EditUser'].includes(to.name)
    && !to.params.id) {

    return next({ name: from.name});
  }

  return next();
});

export default router;
