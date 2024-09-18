import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment.dev';
import { Contacto } from '../models/contacto.model';

@Injectable({
  providedIn: 'root'
})
export class ContactoService {
  private apiUrl = environment.api;

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  getContactos(page: number, itemsPerPage: number, search: string = ''): Observable<any> {
    let params = new HttpParams()
      .set('page', page)
      .set('itemsPerPage', itemsPerPage)
      .set('search', search);

    return this.http.get<any>(this.apiUrl + 'contactos', { params, headers: this.getHeaders() });
  }

  getContacto(id: string): Observable<any> {
    return this.http.get<any>(this.apiUrl+'contacto/'+id, { headers: this.getHeaders() });
  }

  createContacto(contacto: Contacto): Observable<Contacto> {
    return this.http.post<Contacto>(this.apiUrl+'contactos', contacto, { headers: this.getHeaders() });
  }

  updateContacto(id: string, contacto: Contacto): Observable<Contacto> {
    return this.http.put<Contacto>(this.apiUrl+'contactos/'+id, contacto, { headers: this.getHeaders() });
  }

  deleteContacto(id: string): Observable<void> {
    return this.http.delete<void>(this.apiUrl+'contactos'+id, { headers: this.getHeaders() });
  }
}
