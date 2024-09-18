import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ContactoService } from '../../services/contacto.service';

@Component({
  selector: 'app-detalle-contacto',
  templateUrl: './detalle-contacto.component.html',
  styleUrls: ['./detalle-contacto.component.css']
})
export class DetalleContactoComponent implements OnInit {
  contacto: any;
  isLoading: boolean = true;
  errorMessage: string = '';

  constructor(
    private route: ActivatedRoute,
    private contactoService: ContactoService
  ) { }

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.contactoService.getContacto(id).subscribe(data => {
        this.contacto = data;
        this.isLoading = false;
      },
        error => {
          this.errorMessage = 'Hubo un problema al cargar los datos '+error;
          this.isLoading = false;
        }
      );
    }
  }
}
