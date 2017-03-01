import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {SignInFormComponent, SignOutComponent, PageNotFoundComponent} from './components';

const AppRoutes:Routes = [
    {
        path: '',
        redirectTo: '/photos',
        pathMatch: 'full'
    },
    {
        path: 'signin',
        component: SignInFormComponent,
    },
    {
        path: 'signout',
        component: SignOutComponent,
    },
    {
        path: '404',
        component: PageNotFoundComponent
    },
    {
        path: '**',
        component: PageNotFoundComponent
    }
];

export const AppRoutingProviders:any[] = [];

export const AppRouting:ModuleWithProviders = RouterModule.forRoot(AppRoutes);
