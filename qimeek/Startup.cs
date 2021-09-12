using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.HttpsPolicy;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.UI;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.FileProviders;
using Microsoft.Extensions.Hosting;
using qimeek.Data;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using System.Xml.Serialization;

namespace qimeek
{
    public class Startup
    {
        private QimeekConfig _qimeekConfig;
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;

            // read external config
            var mySerializer = new XmlSerializer(typeof(QimeekConfig));
            #if DEBUG
            using var myFileStream = new FileStream(@"..\..\qimeek_prod.xml", FileMode.Open);
            #elif RELEASE
            using var myFileStream = new FileStream("qimeek_prod.xml", FileMode.Open);
            #endif
            _qimeekConfig = (QimeekConfig)mySerializer.Deserialize(myFileStream);
        }

        public IConfiguration Configuration { get; }

        // This method gets called by the runtime. Use this method to add services to the container.
        public void ConfigureServices(IServiceCollection services)
        {
            services.AddSingleton(_qimeekConfig);
            services.AddDbContext<QimeekDbContext>(options =>
                options.UseNpgsql(_qimeekConfig.DbConnectionString)
            );
            services.AddDatabaseDeveloperPageExceptionFilter();

            services.Configure<IdentityOptions>(options =>
            {
                options.Password.RequireUppercase = false;
                options.Password.RequireNonAlphanumeric = false;
            });

            services.AddRazorPages();
        }

        // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
        public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
                app.UseMigrationsEndPoint();
            }
            else
            {
                app.UseExceptionHandler("/Error");
                // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
                app.UseHsts();
            }

            app.UseHttpsRedirection();
            app.UseStaticFiles(new StaticFileOptions
            {
#if RELEASE
                FileProvider = new PhysicalFileProvider("/home/stillnorth/www/qimeek_net5")
#endif
            });

            app.UseRouting();

            app.UseAuthentication();
            app.UseAuthorization();

            app.UseEndpoints(endpoints =>
            {
                endpoints.MapRazorPages();
            });
        }
    }
}
