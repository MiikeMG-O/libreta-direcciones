import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';
import { HomeComponent } from './components/home/home.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NavbarComponent } from './components/navbar/navbar.component';
import { ContactoComponent } from './components/contacto/contacto.component';
import { FontAwesomeModule, FaIconLibrary} from '@fortawesome/angular-fontawesome';
import { faEye, faEdit, faTrashAlt, faUserCircle, faPlusSquare } from '@fortawesome/free-solid-svg-icons';
import { DetalleContactoComponent } from './components/detalle-contacto/detalle-contacto.component';
import { FormularioContactoComponent } from './components/formulario-contacto/formulario-contacto.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    HomeComponent,
    NavbarComponent,
    ContactoComponent,
    DetalleContactoComponent,
    FormularioContactoComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule,
    FontAwesomeModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
  constructor(library: FaIconLibrary) {
    library.addIcons(faEye, faEdit, faTrashAlt, faUserCircle, faPlusSquare);
  }
}
