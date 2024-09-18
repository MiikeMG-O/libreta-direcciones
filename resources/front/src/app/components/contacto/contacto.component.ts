import { Component, OnInit, AfterViewInit } from '@angular/core';
import { ContactoService } from '../../services/contacto.service';
import { Contacto, Telefono, Email, Direccion } from '../../models/contacto.model'; // Ajusta la ruta según tu estructura

declare const $: any; // Necesario para usar jQuery y DataTables

@Component({
  selector: 'app-contacto',
  templateUrl: './contacto.component.html',
  styleUrls: ['./contacto.component.css']
})
export class ContactoComponent implements OnInit, AfterViewInit {
  isLoading: boolean = true;
  totalItems = 0;
  itemsPerPage = 10;
  currentPage = 1;

  constructor(private contactoService: ContactoService) {}

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    this.initDataTable();
  }

  initDataTable(): void {
    $('#contactosTable').DataTable({
      responsive: true,
      destroy: true,
      paging: true,
      searching: true,
      processing: true,
      serverSide: true,
      ajax: (data: any, callback: any) => {
        const page = Math.ceil(data.start / data.length) + 1;
        this.contactoService.getContactos(page, data.length, data.search.value).subscribe(
          response => {
            callback({
              draw: data.draw,
              recordsTotal: response.total,
              recordsFiltered: response.total,
              data: response.data
            });
            this.isLoading = false;
          },
          error => {
            this.isLoading = false;
            console.error('Error fetching data:', error);
          }
        );
      },
      columns: [
        { data: 'nombre', title: 'Nombre' },
        {
          data: 'emails',
          title: 'Emails',
          render: (data: Email[]) => data.map(email => email.direccion).join('<br>')
        },
        {
          data: 'telefonos',
          title: 'Teléfonos',
          render: (data: Telefono[]) => data.map(tel => tel.numero).join('<br>')
        },
        {
          data: 'direcciones',
          title: 'Direcciones',
          render: (data: Direccion[]) => data.map(dir => `${dir.calle}, ${dir.ciudad}, ${dir.estado}, ${dir.codigo_postal}`).join('<br>')
        },
        {
          data: null,
          title: 'Acciones',
          render: (data: Contacto) => `
            <div class="btn-group" role="group" aria-label="Acciones">
              <a href="/contacto/${data.id}" class="btn btn-sm btn-primary" title="Ver Contacto">Ver</a>
              <a href="/contactos/editar/${data.id}" class="btn btn-sm btn-info" title="Editar Contacto">Editar</a>
              <a href="#" class="btn btn-sm btn-danger" title="Eliminar Contacto">Eliminar</a>
            </div>
          `
        }
      ],
      language: {
        processing: "Cargando...",
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando del _START_ al _END_ de _TOTAL_ registros",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "Siguiente",
          previous: "Anterior"
        }
      }
    });
  }
}
