import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api';
import {Auth, User} from '../../models';

@Injectable()
export class UserDataProviderService {
    constructor(@Inject(ApiService) private api:ApiService) {
    }

    getById = (id:number):Promise<User> => {
        return this.api
            .get('/users/' + id)
            .toPromise();
    };

    getAuthByCredentials = (email:string, password:string):Promise<Auth> => {
        return this.api
            .post('/token', {email: email, password: password})
            .toPromise();
    };
}
