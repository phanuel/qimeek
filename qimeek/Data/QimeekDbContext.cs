using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;

#nullable disable

namespace qimeek.Data
{
    public partial class QimeekDbContext : IdentityDbContext<IdentityUser>
    {
        public QimeekDbContext()
        {
        }

        public QimeekDbContext(DbContextOptions<QimeekDbContext> options)
            : base(options)
        {
        }

        public virtual DbSet<Bookmark> Bookmarks { get; set; }
        public virtual DbSet<Directory> Directories { get; set; }
        //public virtual DbSet<User> Users { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            modelBuilder.HasAnnotation("Relational:Collation", "fr_CH.utf8");

            modelBuilder.Entity<Bookmark>(entity =>
            {
                entity.ToTable("bookmarks");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .UseIdentityAlwaysColumn();

                entity.Property(e => e.DateAdded)
                    .HasColumnType("timestamp with time zone")
                    .HasColumnName("date_added");

                entity.Property(e => e.DirectoryId).HasColumnName("directory_id");

                entity.Property(e => e.Title)
                    .HasMaxLength(255)
                    .HasColumnName("title")
                    .HasDefaultValueSql("NULL::character varying");

                entity.Property(e => e.Url)
                    .IsRequired()
                    .HasMaxLength(5000)
                    .HasColumnName("url");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("text");
            });

            modelBuilder.Entity<Directory>(entity =>
            {
                entity.ToTable("directories");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .UseIdentityAlwaysColumn();

                entity.Property(e => e.DateAdded)
                    .HasColumnType("timestamp with time zone")
                    .HasColumnName("date_added");

                entity.Property(e => e.Name)
                    .IsRequired()
                    .HasMaxLength(255)
                    .HasColumnName("name");

                entity.Property(e => e.ParentId).HasColumnName("parent_id");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("text");
            });

            modelBuilder.Entity<User>(entity =>
            {
                entity.ToTable("users");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .UseIdentityAlwaysColumn();

                entity.Property(e => e.Email)
                    .IsRequired()
                    .HasMaxLength(100)
                    .HasColumnName("email");

                entity.Property(e => e.LastConnection)
                    .HasColumnType("timestamp with time zone")
                    .HasColumnName("last_connection");

                entity.Property(e => e.Login)
                    .IsRequired()
                    .HasMaxLength(255)
                    .HasColumnName("login");

                entity.Property(e => e.Password)
                    .IsRequired()
                    .HasMaxLength(50)
                    .HasColumnName("password");

                entity.Property(e => e.RegisterDate)
                    .HasColumnType("timestamp with time zone")
                    .HasColumnName("register_date");
            });

            OnModelCreatingPartial(modelBuilder);
        }

        partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
    }
}
