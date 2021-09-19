using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;
using qimeek.Models;

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

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            modelBuilder.HasAnnotation("Relational:Collation", "fr_CH.utf8");

            modelBuilder.Entity<AspNetRole>(entity =>
            {
                entity.HasIndex(e => e.NormalizedName, "RoleNameIndex")
                    .IsUnique();

                entity.Property(e => e.Name).HasMaxLength(256);

                entity.Property(e => e.NormalizedName).HasMaxLength(256);
            });

            modelBuilder.Entity<AspNetRoleClaim>(entity =>
            {
                entity.HasIndex(e => e.RoleId, "IX_AspNetRoleClaims_RoleId");

                entity.Property(e => e.RoleId).IsRequired();

                entity.HasOne(d => d.Role)
                    .WithMany(p => p.AspNetRoleClaims)
                    .HasForeignKey(d => d.RoleId);
            });

            modelBuilder.Entity<AspNetUser>(entity =>
            {
                entity.HasIndex(e => e.NormalizedEmail, "EmailIndex");

                entity.HasIndex(e => e.NormalizedUserName, "UserNameIndex")
                    .IsUnique();

                entity.Property(e => e.Email).HasMaxLength(256);

                entity.Property(e => e.LockoutEnd).HasColumnType("timestamp with time zone");

                entity.Property(e => e.NormalizedEmail).HasMaxLength(256);

                entity.Property(e => e.NormalizedUserName).HasMaxLength(256);

                entity.Property(e => e.UserName).HasMaxLength(256);
            });

            modelBuilder.Entity<AspNetUserClaim>(entity =>
            {
                entity.HasIndex(e => e.UserId, "IX_AspNetUserClaims_UserId");

                entity.Property(e => e.UserId).IsRequired();

                entity.HasOne(d => d.User)
                    .WithMany(p => p.AspNetUserClaims)
                    .HasForeignKey(d => d.UserId);
            });

            modelBuilder.Entity<AspNetUserLogin>(entity =>
            {
                entity.HasKey(e => new { e.LoginProvider, e.ProviderKey });

                entity.HasIndex(e => e.UserId, "IX_AspNetUserLogins_UserId");

                entity.Property(e => e.LoginProvider).HasMaxLength(128);

                entity.Property(e => e.ProviderKey).HasMaxLength(128);

                entity.Property(e => e.UserId).IsRequired();

                entity.HasOne(d => d.User)
                    .WithMany(p => p.AspNetUserLogins)
                    .HasForeignKey(d => d.UserId);
            });

            modelBuilder.Entity<AspNetUserRole>(entity =>
            {
                entity.HasKey(e => new { e.UserId, e.RoleId });

                entity.HasIndex(e => e.RoleId, "IX_AspNetUserRoles_RoleId");

                entity.HasOne(d => d.Role)
                    .WithMany(p => p.AspNetUserRoles)
                    .HasForeignKey(d => d.RoleId);

                entity.HasOne(d => d.User)
                    .WithMany(p => p.AspNetUserRoles)
                    .HasForeignKey(d => d.UserId);
            });

            modelBuilder.Entity<AspNetUserToken>(entity =>
            {
                entity.HasKey(e => new { e.UserId, e.LoginProvider, e.Name });

                entity.Property(e => e.LoginProvider).HasMaxLength(128);

                entity.Property(e => e.Name).HasMaxLength(128);

                entity.HasOne(d => d.User)
                    .WithMany(p => p.AspNetUserTokens)
                    .HasForeignKey(d => d.UserId);
            });

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
                    .IsRequired()
                    .HasColumnName("user_id");

                entity.HasOne(d => d.Directory)
                    .WithMany(p => p.Bookmarks)
                    .HasForeignKey(d => d.DirectoryId)
                    .HasConstraintName("fk_bookmarks_directories");

                entity.HasOne(d => d.User)
                    .WithMany(p => p.Bookmarks)
                    .HasForeignKey(d => d.UserId)
                    .HasConstraintName("fk_bookmarks_aspnetusers");
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
                    .IsRequired()
                    .HasColumnName("user_id");

                entity.HasOne(d => d.Parent)
                    .WithMany(p => p.InverseParent)
                    .HasForeignKey(d => d.ParentId)
                    .OnDelete(DeleteBehavior.Cascade)
                    .HasConstraintName("fk_directories_directories");

                entity.HasOne(d => d.User)
                    .WithMany(p => p.Directories)
                    .HasForeignKey(d => d.UserId)
                    .HasConstraintName("fk_directories_aspnetusers");
            });

            OnModelCreatingPartial(modelBuilder);
        }

        partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
    }
}
