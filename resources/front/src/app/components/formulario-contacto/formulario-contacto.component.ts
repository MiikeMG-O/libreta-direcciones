import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormArray } from '@angular/forms';
import { ContactoService } from '../../services/contacto.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Contacto } from '../../models/contacto.model';

@Component({
  selector: 'app-formulario-contacto',
  templateUrl: './formulario-contacto.component.html',
  styleUrls: ['./formulario-contacto.component.css']
})
export class FormularioContactoComponent implements OnInit {

  contactoForm: FormGroup;
  isEditMode: boolean = false;
  errorMessage: string | null = null;

  constructor(
    private fb: FormBuilder,
    private contactoService: ContactoService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.contactoForm = this.fb.group({
      nombre: ['', Validators.required],
      apellido: [''],
      empresa: [''],
      telefonos: this.fb.array([]),
      emails: this.fb.array([]),
      direcciones: this.fb.array([]),
      notas: [''],
      pagina_web: [''],
      cumpleanos: ['']
    });
  }

  ngOnInit(): void {
    this.initializeForm();

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.isEditMode = true;
      this.contactoService.getContacto(id).subscribe(contacto => {
        this.contactoForm.patchValue(contacto);
        this.setTelefonos(contacto.telefonos);
        this.setEmails(contacto.emails);
        this.setDirecciones(contacto.direcciones);
      });
    }
  }

  initializeForm() {
    this.contactoForm = this.fb.group({
      nombre: ['', Validators.required],
      apellido: [''],
      empresa: [''],
      telefonos: this.fb.array([]),
      emails: this.fb.array([]),
      direcciones: this.fb.array([]),
      notas: [''],
      pagina_web: [''],
      cumpleanos: ['']
    });
  }

  get telefonos(): FormArray {
    return this.contactoForm.get('telefonos') as FormArray;
  }

  get emails(): FormArray {
    return this.contactoForm.get('emails') as FormArray;
  }

  get direcciones(): FormArray {
    return this.contactoForm.get('direcciones') as FormArray;
  }

  setTelefonos(telefonos: any[]) {
    const telefonosFGs = telefonos.map(telefono => this.fb.group(telefono));
    const telefonosFormArray = this.fb.array(telefonosFGs);
    this.contactoForm.setControl('telefonos', telefonosFormArray);
  }

  setEmails(emails: any[]) {
    const emailsFGs = emails.map(email => this.fb.group(email));
    const emailsFormArray = this.fb.array(emailsFGs);
    this.contactoForm.setControl('emails', emailsFormArray);
  }

  setDirecciones(direcciones: any[]) {
    const direccionesFGs = direcciones.map(direccion => this.fb.group(direccion));
    const direccionesFormArray = this.fb.array(direccionesFGs);
    this.contactoForm.setControl('direcciones', direccionesFormArray);
  }

  addTelefono() {
    this.telefonos.push(this.fb.group({
      numero: [''],
      tipo: ['']
    }));
  }

  removeTelefono(index: number) {
    this.telefonos.removeAt(index);
  }

  addEmail() {
    this.emails.push(this.fb.group({
      direccion: ['']
    }));
  }

  removeEmail(index: number) {
    this.emails.removeAt(index);
  }

  addDireccion() {
    this.direcciones.push(this.fb.group({
      calle: [''],
      ciudad: [''],
      estado: [''],
      codigo_postal: ['']
    }));
  }

  removeDireccion(index: number) {
    this.direcciones.removeAt(index);
  }

  onSubmit() {
    if (this.contactoForm.valid) {
      const contactoData: Contacto = this.contactoForm.value;
      const id = this.route.snapshot.paramMap.get('id');

      const saveOperation = this.isEditMode && id
        ? this.contactoService.updateContacto(id, contactoData)
        : this.contactoService.createContacto(contactoData);

      saveOperation.subscribe({
        next: () => {
          this.router.navigate(['/home']);
        },
        error: (err) => {
          this.errorMessage = err.error.message || 'Ocurrió un error al procesar la solicitud.';
        }
      });
    } else {
      this.errorMessage = 'Por favor, completa el formulario correctamente.';
    }
  }

}
