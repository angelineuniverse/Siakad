import React, { Component, ReactNode, Suspense } from "react";
import { ResponseColumn, ModelTable } from "./model";
import clsx from "clsx";
import { get } from "lodash";
import { format } from "date-fns";
const Button = React.lazy(() => import("../button/button"));
const Skeleton = React.lazy(() => import("../skeleton/skeleton"));
const Pagination = React.lazy(() => import("../pagination/pagination"));
class Table extends Component<ModelTable> {
  render(): ReactNode {
    return (
      <div>
        <div className={clsx("block", this.props.className)}>
          <div className="flex justify-end items-center w-full">
            {this.props.useHeadline && (
              <div className="block mr-auto">
                <h1 className=" font-interbold text-lg">
                  {this.props?.title ?? "Table Headline"}
                </h1>
                <p className=" font-interregular text-xs">
                  {this.props?.description ?? "Lihat semua informasi"}
                </p>
              </div>
            )}
            {this.props.useCreate && (
              <Button
                title="Buat Baru"
                theme="primary"
                size="extrasmall"
                width="block"
                isLoading={this.props.loadingCreate}
                onClick={this.props.create}
              />
            )}
          </div>
          <div className="overflow-hidden border border-gray-300 md:rounded-lg mt-3">
            <table className="w-full min-w-full divide-y divide-gray-200">
              <thead className=" font-interbold bg-gradient-to-b from-gray-100 to-gray-100">
                {this.props.column.length < 1 && (
                  <tr>
                    <th className="py-3 text-center px-4">
                      <Skeleton type="text" />
                    </th>
                    <th className="py-3 text-center px-4">
                      <Skeleton type="text" />
                    </th>
                    <th className="py-3 text-center px-4">
                      <Skeleton type="text" />
                    </th>
                    <th className="py-3 text-center px-4">
                      <Skeleton type="text" />
                    </th>
                  </tr>
                )}
                {this.props.column.length > 0 && (
                  <tr className=" uppercase">
                    <th className="py-3 text-center text-xs px-4">No</th>
                    {this.props.column?.map((e: any) => {
                      return (
                        <th
                          key={e.name + "-" + e.type}
                          className={clsx(
                            "py-3 text-xs px-4",
                            e.type === "action" || e.type === "status"
                              ? "text-center"
                              : "text-start",
                            e.classNameColumn
                          )}
                        >
                          {e.type === "action" ? "action" : e.name}
                        </th>
                      );
                    })}
                  </tr>
                )}
              </thead>
              <tbody>
                {this.props.column.length < 1 &&
                  [...Array(this.props.skeletonRow ?? 4)].map((row, index) => {
                    return (
                      <Suspense key={index + "key"}>
                        <tr>
                          <td className="py-3 text-center px-4">
                            <Skeleton type="random" />
                          </td>
                          <td className="py-3 text-center px-4">
                            <Skeleton type="random" />
                          </td>
                          <td className="py-3 text-center px-4">
                            <Skeleton type="random" />
                          </td>
                          <td className="py-3 text-center px-4">
                            <Skeleton type="random" />
                          </td>
                        </tr>
                      </Suspense>
                    );
                  })}
                {this.props.data?.map((row, index) => {
                  return (
                    <tr key={"item-" + (row.id ?? index + 1)}>
                      <td className="py-3 text-xs text-center font-intersemibold px-4">
                        {index + 1}
                      </td>
                      {this.props.column?.map((col: ResponseColumn) => {
                        return (
                          <td
                            key={`item-row-${col.type}-${col.key}-${index + 1}`}
                            className={clsx(
                              "py-3 text-xs text-start font-interregular px-4"
                            )}
                          >
                            {col.type === "array" && (
                              <>
                                {col.child?.map(
                                  (item: ResponseColumn, n: number) => {
                                    return (
                                      <div
                                        key={`${item.key}-${item.type}-${
                                          col.name
                                        }-${index + n + 1}`}
                                        className="w-auto"
                                      >
                                        {item.type === "string" && (
                                          <p className={clsx(item?.className)}>
                                            {get(row, item.key)}
                                          </p>
                                        )}
                                        {item.type === "date" && (
                                          <span className={item?.className}>
                                            {format(
                                              get(row, item.key),
                                              "dd MMMM yyyy"
                                            )}
                                          </span>
                                        )}
                                        {item.type === "datetime" && (
                                          <span className={item?.className}>
                                            {format(
                                              get(row, item.key),
                                              "dd MMMM yyyy HH:mm"
                                            )}
                                          </span>
                                        )}
                                        {item.type === "object" && (
                                          <div
                                            className={clsx(
                                              "flex flex-wrap h-fit w-fit items-center text-pretty gap-1",
                                              item.className
                                            )}
                                          >
                                            {item.child?.map(
                                              (
                                                itemx: ResponseColumn,
                                                indexs: number
                                              ) => {
                                                return (
                                                  <div
                                                    key={`${itemx.key}-${
                                                      itemx.type
                                                    }-${col.name}-${
                                                      index + indexs + 1
                                                    }`}
                                                  >
                                                    {itemx.type ===
                                                      "string" && (
                                                      <span
                                                        className={
                                                          itemx?.className
                                                        }
                                                      >
                                                        {get(row, itemx.key)}
                                                      </span>
                                                    )}
                                                    {itemx.type === "date" && (
                                                      <span
                                                        className={
                                                          itemx?.className
                                                        }
                                                      >
                                                        {format(
                                                          get(row, itemx.key),
                                                          "dd MMMM yyyy"
                                                        )}
                                                      </span>
                                                    )}
                                                    {itemx.type ===
                                                      "datetime" && (
                                                      <span
                                                        className={
                                                          itemx?.className
                                                        }
                                                      >
                                                        {format(
                                                          get(row, itemx.key),
                                                          "dd MMMM yyyy HH:mm"
                                                        )}
                                                      </span>
                                                    )}
                                                  </div>
                                                );
                                              }
                                            )}
                                          </div>
                                        )}
                                      </div>
                                    );
                                  }
                                )}
                              </>
                            )}
                            {col.type === "object" && (
                              <div
                                className={clsx(
                                  "flex flex-wrap h-fit w-fit items-center text-pretty gap-1",
                                  col.className
                                )}
                              >
                                {col.child?.map(
                                  (item: ResponseColumn, j: number) => {
                                    return (
                                      <div
                                        key={`${item.key}-${item.type}-${
                                          index + j + 1
                                        }`}
                                        className="w-fit"
                                      >
                                        {item.type === "string" && (
                                          <span className={item?.className}>
                                            {get(row, item.key)}
                                          </span>
                                        )}
                                        {item.type === "date" && (
                                          <span className={item?.className}>
                                            {format(
                                              get(row, item.key),
                                              "dd MMMM yyyy"
                                            )}
                                          </span>
                                        )}
                                        {item.type === "datetime" && (
                                          <span className={item?.className}>
                                            {format(
                                              get(row, item.key),
                                              "dd MMMM yyyy HH:mm"
                                            )}
                                          </span>
                                        )}
                                        <span>
                                          {" "}
                                          {j !== (col?.child?.length ?? 0) - 1
                                            ? "-"
                                            : " "}
                                        </span>
                                      </div>
                                    );
                                  }
                                )}
                              </div>
                            )}
                            {col.type === "date" && (
                              <span>
                                {format(row[col?.key ?? ""], "dd MMMM yyyy")}
                              </span>
                            )}
                            {col.type === "string" && (
                              <span className={col.className}>
                                {get(row, col.key) ?? "-"}
                              </span>
                            )}
                            {col.type === "status" && (
                              <p className="text-center">
                                <span
                                  className={clsx(
                                    "rounded-xl py-1.5 px-2 text-center font-intermedium",
                                    `bg-${row[col?.key ?? ""]?.color}-100`,
                                    `text-${row[col?.key ?? ""]?.color}-800`
                                  )}
                                >
                                  {get(row, col.key)?.title}
                                </span>
                              </p>
                            )}
                            {col.type === "action" && (
                              <div className="flex justify-center flex-row gap-x-2 px-4">
                                {col.ability?.map((ability) => {
                                  return (
                                    <Suspense key={`${ability}-${index + 1}`}>
                                      {ability === "DELETE" && (
                                        <Suspense>
                                          <Button
                                            title="Hapus"
                                            theme="error"
                                            size="extrasmall"
                                            width="block"
                                            onClick={() =>
                                              this.props.delete!(row)
                                            }
                                          />
                                        </Suspense>
                                      )}
                                      {ability === "SHOW" && (
                                        <Button
                                          title="Lihat"
                                          theme="warning"
                                          size="extrasmall"
                                          width="block"
                                          onClick={() => this.props.show!(row)}
                                        />
                                      )}
                                      {ability === "EDIT" && (
                                        <Button
                                          title="Ubah"
                                          theme="success"
                                          size="extrasmall"
                                          width="block"
                                          onClick={() => this.props.edit!(row)}
                                        />
                                      )}
                                      {ability === "ADD" && (
                                        <Button
                                          title="Tambah"
                                          theme="primary"
                                          size="extrasmall"
                                          width="block"
                                          onClick={() => this.props.add!(row)}
                                        />
                                      )}
                                    </Suspense>
                                  );
                                })}
                              </div>
                            )}
                          </td>
                        );
                      })}
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
          <div className=" mt-5 flex justify-end">
            <Suspense>
              <Pagination {...this.props.property} />
            </Suspense>
          </div>
        </div>
      </div>
    );
  }
}

export default Table;
