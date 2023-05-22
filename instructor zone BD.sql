CREATE TABLE [Documentos] (
  [IdDocumento] INT,
  [TituloDocumento] VARCHAR(50),
  [IdAutor] INT,
  [Status] INT,
  [ContenidoDocumento] VARCHAR(MAX),
  PRIMARY KEY ([IdDocumento])
);

CREATE TABLE [Status] (
  [IdStatus] INT,
  [NombreStatus] VARCHAR(30),
  PRIMARY KEY ([IdStatus])
);

ALTER TABLE [Documentos]
ADD CONSTRAINT [FK_Documentos_Status] FOREIGN KEY ([Status])
REFERENCES [Status]([IdStatus]);

CREATE TABLE [Dietas] (
  [TipoCuerpo] INT,
  [Objetivo] INT,
  [DocumentoId] INT,
  PRIMARY KEY ([DocumentoId])
);

CREATE TABLE [TipoCuerpo] (
  [IdTipoCuerpo] INT,
  [NombreTipoCuerpo] VARCHAR(30),
  PRIMARY KEY ([IdTipoCuerpo])
);

ALTER TABLE [Dietas]
ADD CONSTRAINT [FK_Dietas_TipoCuerpo] FOREIGN KEY ([TipoCuerpo])
REFERENCES [TipoCuerpo]([IdTipoCuerpo]);

CREATE TABLE [Entrenamientos] (
  [Sexo] INT,
  [Nivel] INT,
  [DocumentoId] INT,
  PRIMARY KEY ([DocumentoId])
);

CREATE TABLE [Usuarios] (
  [IdUsuario] INT,
  [NombreUsuario] VARCHAR(50),
  [Correo] VARCHAR(50),
  [Contraseña] VARCHAR(50),
  [Rol] INT,
  PRIMARY KEY ([IdUsuario])
);

CREATE TABLE [Nivel] (
  [IdNivel] INT,
  [NombreNivel] VARCHAR(30),
  PRIMARY KEY ([IdNivel])
);

ALTER TABLE [Entrenamientos]
ADD CONSTRAINT [FK_Entrenamientos_Nivel] FOREIGN KEY ([Nivel])
REFERENCES [Nivel]([IdNivel]);

CREATE TABLE [Sexo] (
  [IdSexo] INT,
  [NombreSexo] VARCHAR(30),
  PRIMARY KEY ([IdSexo])
);

ALTER TABLE [Entrenamientos]
ADD CONSTRAINT [FK_Entrenamientos_Sexo] FOREIGN KEY ([Sexo])
REFERENCES [Sexo]([IdSexo]);

CREATE TABLE [Rol] (
  [IdRol] INT,
  [NombreRol] VARCHAR(30),
  PRIMARY KEY ([IdRol])
);

ALTER TABLE [Usuarios]
ADD CONSTRAINT [FK_Usuarios_Rol] FOREIGN KEY ([Rol])
REFERENCES [Rol]([IdRol]);

CREATE TABLE [Objetivo] (
  [IdObjetivo] INT,
  [NombreObjetivo] VARCHAR(30),
  PRIMARY KEY ([IdObjetivo])
);

ALTER TABLE [Dietas]
ADD CONSTRAINT [FK_Dietas_Objetivo] FOREIGN KEY ([Objetivo])
REFERENCES [Objetivo]([IdObjetivo]);

ALTER TABLE [Entrenamientos]
ADD CONSTRAINT [FK_Entrenamientos_DocumentoId] FOREIGN KEY ([DocumentoId])
REFERENCES [Documentos]([IdDocumento]);

ALTER TABLE [Dietas]
ADD CONSTRAINT [FK_Dietas_DocumentoId] FOREIGN KEY ([DocumentoId])
REFERENCES [Documentos]([IdDocumento]);

