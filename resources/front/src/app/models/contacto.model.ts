export interface Telefono {
  numero: string;
  tipo: string;
}

export interface Email {
  direccion: string;
}

export interface Direccion {
  calle: string;
  ciudad: string;
  estado: string;
  codigo_postal: string;
}

export class Contacto {
  id: string;
  nombre: string;
  empresa: string;
  apellido?: string;
  telefonos: Telefono[];
  emails: Email[];
  direcciones: Direccion[];
  notas?: string;
  pagina_web?: string;
  cumpleanos?: Date;

  constructor(
    id: string,
    nombre: string,
    empresa: string,
    apellido?: string,
    telefonos: Telefono[] = [],
    emails: Email[] = [],
    direcciones: Direccion[] = [],
    notas?: string,
    pagina_web?: string,
    cumpleanos?: Date
  ) {
    this.id = id;
    this.nombre = nombre;
    this.empresa = empresa;
    this.apellido = apellido;
    this.telefonos = telefonos;
    this.emails = emails;
    this.direcciones = direcciones;
    this.notas = notas;
    this.pagina_web = pagina_web;
    this.cumpleanos = cumpleanos;
  }
}
