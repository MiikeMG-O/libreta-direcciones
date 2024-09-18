import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { HomeComponent } from './components/home/home.component';
import { DetalleContactoComponent } from './components/detalle-contacto/detalle-contacto.component';
import { FormularioContactoComponent } from './components/formulario-contacto/formulario-contacto.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'home', component: HomeComponent },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: 'contacto/:id', component: DetalleContactoComponent },
  { path: 'contactos/crear', component: FormularioContactoComponent },
  { path: 'contactos/editar/:id', component: FormularioContactoComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
