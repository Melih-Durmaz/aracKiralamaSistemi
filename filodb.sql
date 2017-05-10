create table musteri (
id int primary key auto_increment not null,
aktif bool not null default true,
isim varchar(32) not null,
tarih date
);

create table arac (
plaka varchar(16) primary key not null unique,
musteri_id int not null default 1,
aktif bool not null default false,
ozmal bool not null default false,
foreign key (musteri_id) references musteri(id)
);

create table durum (
id int primary key auto_increment not null,
arac_id varchar(16),
tarih date not null,
bakimda bool not null default false,
km float not null default 0,
depo float not null default 0,
foreign key (arac_id) references arac(plaka) on delete set null
);

create table bakim (
id int primary key auto_increment not null,
durum_id int not null unique,
tarih date not null,
note text,
tamirde bool not null default false,
foreign key (durum_id) references durum(id)
);

create table tamir (
bakim_id int not null unique,
tarih date not null,
note text,
hurda bool not null default false,
foreign key (bakim_id) references bakim(id)
);

insert into musteri values(1,false,'kiralamafirma','1995-01-01');
